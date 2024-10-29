<?php


namespace App\Http\Controllers\Member\Reports;


use App\Models\AccountType;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ReturnPurchase;
use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\StockReport;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryReportController extends BaseReportController
{
    private function set_from_to_date($request)
    {
        if(!$request->returnData)
        {
            $this->searchDate($request);
        }else{
            $this->fromDate = $request->fromDate;
            $this->toDate = $request->toDate;
            $this->pre_fromDate = $request->pre_fromDate;
            $this->pre_toDate = $request->pre_toDate;
        }
    }

    public function index(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        // $stock_product = StockReport::whereBetween('date', [$this->fromDate, $this->toDate]);

        $items = Item::isNotService()->pluck('id')->toArray();

        $stock_product = new StockReport();
        $stock_product = $this->authCompany($stock_product, $request);
        $stock_product = $stock_product->whereIn('item_id', $items)->orderBy('item_id', 'asc')->distinct('item_id')->select('item_id')->pluck('item_id')->toArray();

        $price = $inventory_price = $pre_price = $pre_inventory_price = 0;
        $closingInventories = [];
        $openingInventories = $preOpeningInventories = [];
        $last_category = '';

        foreach ($stock_product as $key => $value)
        {
            $stock = StockReport::where('item_id', $value);
            $stock = $this->authCompany($stock, $request);
            $closing = $stock->where('date', '<=', $this->toDate)->orderBy('date', 'desc')->first();

            $stock = StockReport::where('item_id', $value);
            $stock = $this->authCompany($stock, $request);
            $opening = $stock->where('date', '>=', $this->fromDate)->orderBy('date', 'asc')->first();

            $stock = StockReport::where('item_id', $value);
            $stock = $this->authCompany($stock, $request);
            $pre_close_stock_report = $stock->where('date', '>=' ,$this->pre_fromDate)->orderBy('date', 'asc')->first();

            // if($pre_close_stock_report)
            // dd($pre_close_stock_report);

            $close_qty = $closing ? $closing->closing_qty : 0;
            $open_qty = $opening ? $opening->opening_stock : 0;

            $previous_open_qty = $pre_close_stock_report ? $pre_close_stock_report->opening_stock : 0;
            $previous_close_qty = $opening ? create_float_format($opening->opening_stock) : 0;

            $request['stock_from_date'] = $this->fromDate;
            $request['stock_to_date'] = $this->toDate;
            $item_price = $this->countStockPrice([], $request, $value);

            if (empty($item_price['item_price'])) {
                $item_qty_price = 0;
            } else {
                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
            }

            $product = Item::withTrashed()->find($value);

            if( $key == 0 || $last_category != $product->category->display_name)
            {
                $total_per_category = $pre_total_per_category = 0;
            }

            $qty = $closing ? $closing->closing_qty : 0;
            $closing['qty'] = create_float_format($qty);
            $closing['category'] = $last_category = $product->category->display_name;
            $closing['unit'] = $product->unit;
            $closing['item_price'] = $item_qty_price;

            $inventory_price +=  $qty*$item_qty_price;
            $total_per_category += $qty*$item_qty_price;

            $closing['total_per_category'] = $total_per_category;


            $request['stock_from_date'] = $this->pre_fromDate;
            $request['stock_to_date'] = $this->pre_toDate;
            $item_price = $this->countStockPrice([], $request, $value);

            if (empty($item_price['item_price'])) {
                $item_qty_price = 0;
            } else {
                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
            }


            if($opening && $opening->opening_stock != 0)
            {
                $openingInventories[$value] = $opening;
                $openingInventories[$value]['qty'] = $opening ? $opening->opening_stock : 0;;
                $openingInventories[$value]['item_price'] = $qty_price = $item_qty_price == 0 ? $product->price : $item_qty_price;
                $price += $opening ? ($opening->opening_stock * $qty_price) : 0;
            }

            if($previous_open_qty != 0 && $pre_close_stock_report)
            {
                $request['stock_from_date'] = Carbon::parse($this->pre_fromDate)->subYear(1);
                $request['stock_to_date'] = Carbon::parse($this->pre_toDate)->subYear(1);
                $pre_item_price = $this->countStockPrice([], $request, $value);

                if (empty($pre_item_price['item_price'])) {
                    $pre_item_qty_price = 0;
                } else {
                    $pre_item_qty_price = create_float_format($pre_item_price['item_price']['price_qty']);
                }


                $preOpeningInventories[$value] = $pre_close_stock_report;
                $preOpeningInventories[$value]['qty'] = $previous_open_qty;
                $preOpeningInventories[$value]['item_price'] = $pre_qty_price = $pre_item_qty_price == 0 ? $product->price : $pre_item_qty_price;
                $pre_price += ($previous_open_qty*$pre_qty_price);
            }

            $qty_price = $item_qty_price == 0 ? $product->price : $item_qty_price;
            $item_qty_price = create_float_format($qty_price);
            $closing['pre_item_price'] = $item_qty_price;
            $closing['pre_qty'] = $previous_close_qty;
            $pre_inventory_price += ($previous_close_qty*$item_qty_price);
            $pre_total_per_category += ($previous_close_qty*$item_qty_price);
            $closing['pre_total_per_category'] = $pre_total_per_category;

            if(
                $qty != 0
                || $close_qty > 0
                || $total_per_category > 0
                || $pre_total_per_category > 0
            )
            {
                $closingInventories[$value] = $closing;
            }


        }


        $data['inventories'] = collect($closingInventories)->sortBy('category');
        $data['preOpeningInventories'] = $preOpeningInventories;
        $data['openingInventories'] = $openingInventories;
        $data['total_inventory'] = create_float_format($inventory_price,2);
        $data['openingStock'] = $price;

        $data['pre_total_inventory'] = $pre_inventory_price;
        $data['pre_openingStock'] = $pre_price;


        if($request->returnData)
        {
            return $data;
        }else{

            $data['report_title'] = "Inventory <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-inventory', $data);
        }
    }

//    public function index(Request $request, $data = [])
//    {
//        $this->set_from_to_date($request);
//
//        $stock_product = StockReport::whereBetween('date', [$this->fromDate, $this->toDate]);
//        $stock_product = $this->authCompany($stock_product, $request);
//        $stock_product = $stock_product->orderBy('item_id', 'asc')->distinct('item_id')->select('item_id')->pluck('item_id')->toArray();
//
//        $price = $inventory_price = $pre_price = $pre_inventory_price = 0;
//        $closingInventories = [];
//        $openingInventories = $preOpeningInventories = [];
//        $last_category = '';
//        foreach ($stock_product as $key => $value)
//        {
//            $stock = StockReport::where('item_id', $value);
//            $stock = $this->authCompany($stock, $request);
//            $closing = $stock->where('date', '<=', $this->toDate)->orderBy('date', 'desc')->first();
//
//            $stock = StockReport::where('item_id', $value);
//            $stock = $this->authCompany($stock, $request);
//            $opening = $stock->where('date', '>=', $this->fromDate)->orderBy('date', 'asc')->first();
//
//            $close_stock_report = StockReport::whereDate('date', '<=' ,$this->toDate)->where('item_id', $value)->orderBy('date', 'desc')->first();
//            $open_stock_report = StockReport::whereDate('date', '>=' ,$this->fromDate)->where('item_id', $value)->orderBy('date', 'asc')->first();
//            $pre_close_stock_report = StockReport::whereDate('date', '<=' ,$this->fromDate)->where('item_id', $value)->orderBy('date', 'asc')->first();
//
//            $closing['close_qty'] = $close_qty = $close_stock_report ? ($close_stock_report->opening_stock + $close_stock_report->purchase_qty - $close_stock_report->purchase_return_qty - $close_stock_report->sale_qty + $close_stock_report->sale_return_qty) : 0;
//
//            $closing['open_qty']= $open_qty = $open_stock_report ?  $open_stock_report->opening_stock : 0;
//
//            $closing['previous_open_qty'] = $previous_open_qty = $pre_close_stock_report ? $pre_close_stock_report->opening_stock : 0;
//            $closing['previous_close_qty'] = $previous_close_qty = $open_stock_report ? create_float_format($open_stock_report->opening_stock) : 0;
//
//            $request['stock_from_date'] = $this->fromDate;
//            $request['stock_to_date'] = $this->toDate;
//            $item_price = $this->countStockPrice([], $request, $value);
//
//            if (empty($item_price['item_price'])) {
//                $item_qty_price = 0;
//            } else {
//                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
//            }
//
//            if( $key == 0 || $last_category != $closing->item->category->display_name)
//            {
////                $data['total_category_qty'] = isset($total_category_qty) ? $total_category_qty : 0;
////                $data['pre_total_category_qty'] = isset($pre_total_category_qty) ? $pre_total_category_qty : 0;
////                $total_category_qty = $pre_total_category_qty =  0;
//                $total_per_category = $pre_total_per_category = 0;
//
//            }
//
//            $qty = $closing->opening_stock + $closing->purchase_qty - $closing->purchase_return_qty - $closing->sale_qty + $closing->sale_return_qty;
//            $closing['qty'] = create_float_format($qty);
//            $closing['category'] = $last_category = $closing->item->category->display_name;
//            $closing['unit'] = $closing->item->unit;
//            $closing['item_price'] = $item_qty_price;
//
//            $inventory_price +=  $qty*$item_qty_price;
//            $total_per_category += $qty*$item_qty_price;
//
//            $closing['total_per_category'] = $total_per_category;
//
//
//            $request['stock_from_date'] = $this->pre_fromDate;
//            $request['stock_to_date'] = $this->pre_toDate;
//            $item_price = $this->countStockPrice([], $request, $value);
//
//            if (empty($item_price['item_price'])) {
//                $item_qty_price = 0;
//            } else {
//                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
//            }
//
//            $product = Item::withTrashed()->find($value);
//
//            if($opening && $opening->opening_stock != 0)
//            {
//                $openingInventories[$value] = $opening;
//                $openingInventories[$value]['item_price'] = $qty_price = $item_qty_price == 0 ? $product->price : $item_qty_price;
//                $price += $opening ? ($opening->opening_stock * $qty_price) : 0;
//            }
//
//            $pre_opening_stock_report = StockReport::whereDate('date', '<=' ,$this->pre_fromDate)->where('item_id', $value)->orderBy('date', 'asc')->first();
//
//            if($previous_open_qty != 0 && $pre_opening_stock_report)
//            {
//                $request['stock_from_date'] = Carbon::parse($this->pre_fromDate)->subYear(1);
//                $request['stock_to_date'] = Carbon::parse($this->pre_toDate)->subYear(1);
//                $pre_item_price = $this->countStockPrice([], $request, $value);
//
//                if (empty($pre_item_price['item_price'])) {
//                    $pre_item_qty_price = 0;
//                } else {
//                    $pre_item_qty_price = create_float_format($pre_item_price['item_price']['price_qty']);
//                }
//
//                $preOpeningInventories[$value] = $pre_close_stock_report;
//                $preOpeningInventories[$value]['qty'] = $previous_open_qty;
//                $preOpeningInventories[$value]['item_price'] = $pre_qty_price = $pre_item_qty_price == 0 ? $product->price : $pre_item_qty_price;
//                $pre_price += ($previous_open_qty*$pre_qty_price);
//            }
//
//            $qty_price = $item_qty_price == 0 ? $product->price : $item_qty_price;
//            $item_qty_price = create_float_format($qty_price);
//            $closing['pre_item_price'] = $item_qty_price;
//            $closing['pre_qty'] = $previous_close_qty;
//            $pre_inventory_price += ($previous_close_qty*$item_qty_price);
//            $pre_total_per_category += ($previous_close_qty*$item_qty_price);
//            $closing['pre_total_per_category'] = $pre_total_per_category;
//
//            if(
//                $qty != 0
//                || $close_qty > 0
//                || $total_per_category > 0
//                || $pre_total_per_category > 0
//            )
//            {
//                $closingInventories[$value] = $closing;
//            }
//
//
//        }
//
//
//        // dd($closingInventories);
////        $expect = ['opening_stock', 'company_id', 'product_code', 'sale_qty', 'created_at', 'updated_at', 'purchase_return_qty', 'sale_return_qty', 'ProductDateID'];
//        $data['inventories'] = collect($closingInventories)->sortBy('category');
//        // $data['inventories'] = collect($closingInventories)->where('qty', '>',0)->sortBy('category');
//
//
//        $data['preOpeningInventories'] = $preOpeningInventories;
//        $data['openingInventories'] = $openingInventories;
//        $data['total_inventory'] = create_float_format($inventory_price,2);
//        $data['openingStock'] = $price;
//
//        $data['pre_total_inventory'] = $pre_inventory_price;
//        $data['pre_openingStock'] = $pre_price;
//
//
//        if($request->returnData)
//        {
//            return $data;
//        }else{
//
//            $data['report_title'] = "Inventory <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
//            $data = $this->company($data);
//            return view('member.reports.balance_sheet.head_accounts.print-inventory', $data);
//        }
//    }

    public function sale_details(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

//        $sale = AccountType::where('name', '=', "sales")->select('id')->first();
//
//        $data_sale = $sale->account_head_balance();
//        $data_sale = $pre_sale = $this->authCompany($data_sale, $request);
//        $data_sale = $data_sale->latestAccountBalance($this->toDate)->first();
//        $pre_sale = $pre_sale->previousAccountBalance($this->fromDate)->first();
//
//
//        $data['sales'] = $data_sale ? $data_sale->balance * (-1) : 0;
//        $data['pre_sales'] = $pre_sale ? $pre_sale->balance * (-1) : 0;

        $sales = $sale_details = SaleDetails::whereBetween('date', [$this->fromDate, $this->toDate]);
        $sales = $this->authCompany($sales, $request);
        $data['total_sales'] = $sales->sum('total_price');

        $pre_fromDate = $data['pre_fromDate'] = $this->pre_fromDate;
        $pre_toDate = $data['pre_toDate'] = $this->pre_toDate;


        $pre_sales = $pre_sale_details = SaleDetails::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_sales = $this->authCompany($pre_sales, $request);
        $data['pre_total_sales'] = $pre_sales->sum('total_price');

        $sale_details = $this->authCompany($sale_details, $request);
        $pre_sale_details = $this->authCompany($pre_sale_details, $request);

        $data['sale_details'] = $sale_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();

        $data['pre_sale_details'] = $pre_sale_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();


        if($request->returnData)
        {
            return $data;
        }else{

            $data['report_title'] = "Sales  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-sales', $data);
        }
    }

    public function purchase_details(Request $request, $data = [])
    {

        $this->set_from_to_date($request);

        $pre_fromDate = $data['pre_fromDate'] = $this->pre_fromDate;
        $pre_toDate = $data['pre_toDate'] = $this->pre_toDate;

        $purchase_bank_charges = Purchase::whereBetween('date', [$this->fromDate, $this->toDate]);
        $purchase_bank_charges = $this->authCompany($purchase_bank_charges, $request);
        $data['total_purchases_bank_charge'] = $purchase_bank_charges->sum('bank_charge')+$purchase_bank_charges->sum('unload_cost')+$purchase_bank_charges->sum('transport_cost');

        $pre_purchases_bank_charges = Purchase::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_purchases_bank_charges = $this->authCompany($pre_purchases_bank_charges, $request);
        $data['pre_total_purchases_bank_charge'] = $pre_purchases_bank_charges->sum('bank_charge')+$pre_purchases_bank_charges->sum('unload_cost')+$pre_purchases_bank_charges->sum('transport_cost');

        $purchases = $purchase_details = PurchaseDetail::whereBetween('date', [$this->fromDate, $this->toDate]);
        $purchases = $this->authCompany($purchases, $request);
        $data['total_purchases_details'] = $purchases->sum('total_price');
        $data['total_purchases'] = $data['total_purchases_details']+$data['total_purchases_bank_charge'];

        $pre_purchases = $pre_purchase_details = PurchaseDetail::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_purchases = $this->authCompany($pre_purchases, $request);
        $data['pre_total_purchases_details'] = $pre_purchases->sum('total_price');
        $data['pre_total_purchases'] = $data['pre_total_purchases_details'] + $data['pre_total_purchases_bank_charge'];

        $purchase_details = $this->authCompany($purchase_details, $request);
        $data['purchase_details'] = $purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();

        $pre_purchase_details = $this->authCompany($pre_purchase_details, $request);
        $data['pre_purchase_details'] = $pre_purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();

        if($request->returnData)
        {
            return $data;
        }else{
            $data['report_title'] = "Purchases  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-purchases', $data);
        }
    }

    public function purchase_return_details(Request $request, $data = [])
    {

        $this->set_from_to_date($request);

        $pre_fromDate = $data['pre_fromDate'] = $this->pre_fromDate;
        $pre_toDate = $data['pre_toDate'] = $this->pre_toDate;


        $purchase_details = ReturnPurchase::whereBetween('return_date', [$this->fromDate, $this->toDate]);
        $purchase_details = $this->authCompany($purchase_details, $request);


        $data['purchase_return_details'] = $purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(return_qty) as total_qty, avg(return_price) as sum_total_price')
            ->get();

        $data['total_purchases_return'] =  $purchase_details->sum('return_qty')*$purchase_details->avg('return_price');

        $pre_purchase_details = ReturnPurchase::whereBetween('return_date', [$pre_fromDate, $pre_toDate]);
        $pre_purchase_details = $this->authCompany($pre_purchase_details, $request);
        $data['pre_purchase_return_details'] = $pre_purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(return_qty) as total_qty, avg(return_price) as sum_total_price')
            ->get();

        $pre_total_purchases_return =  $pre_purchase_details->sum('return_qty')*$pre_purchase_details->avg('return_price');

        $data['pre_total_purchases_return'] = $pre_total_purchases_return;

        if($request->returnData)
        {
            return $data;
        }else{
            $data['report_title'] = "Purchases Return <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-purchases-return', $data);
        }
    }

    public function sale_return_details(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $pre_fromDate = $data['pre_fromDate'] = $this->pre_fromDate;
        $pre_toDate = $data['pre_toDate'] = $this->pre_toDate;


        $sale_details = SaleReturn::whereBetween('return_date', [$this->fromDate, $this->toDate]);

        $pre_sale_details = SaleReturn::whereBetween('return_date', [$pre_fromDate, $pre_toDate]);

        $sale_details = $this->authCompany($sale_details, $request);
        $pre_sale_details = $this->authCompany($pre_sale_details, $request);

        $data['sale_return_details'] = $sale_details->groupBy('item_id')
            ->selectRaw('*, sum(return_qty) as total_qty, avg(return_price) as sum_total_price')
            ->get();

        $data['total_sales_return'] = $sale_details->sum('return_qty')*$sale_details
                ->avg('return_price');

        $data['pre_sale_return_details'] = $pre_sale_details->groupBy('item_id')
            ->selectRaw('*, sum(return_qty) as total_qty, avg(return_price) as sum_total_price')
            ->get();

        $data['pre_total_sales_return'] = $pre_sale_details->sum('return_qty')*$pre_sale_details->avg('return_price');

        if($request->returnData)
        {
            return $data;
        }else{

            $data['report_title'] = "Sales Return <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-sales-return', $data);
        }
    }



}
