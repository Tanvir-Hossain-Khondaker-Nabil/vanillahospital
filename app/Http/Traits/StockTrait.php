<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 11:47 AM
 */

namespace App\Http\Traits;


use App\Jobs\ChangeStockReportJob;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\StockReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait StockTrait
{
    public function createStockHistory($item_id, $qty, $flag="stock in", $p_stock=0, $c_stock=0)
    {
        $data = [];
        $data['item_id'] = $item_id;
        $data['stock'] = create_float_format($qty);
        $data['previous_stock'] = create_float_format($p_stock);
        $data['current_stock'] = create_float_format($c_stock);
        $data['flag'] = $flag;
        StockHistory::create($data);
    }


    public function stockIn($item_id, $qty, $flag="stock in")
    {
        $data = [];
        $data['item_id'] = $item_id;
        $qty = create_float_format($qty);

        $stockCheck = Stock::where('item_id', $item_id)->first();
        if(!$stockCheck)
        {
            $data['stock'] = $qty;
            Stock::create($data);
            $p_stock = 0;
            $c_stock = $qty;
        }else{
            $p_stock = $stockCheck->stock-$qty;
            $c_stock = $stockCheck->stock;

            $data['stock'] = $c_stock = create_float_format($c_stock);

            if(in_array($flag, ['delete sale']))
            {
                $data['stock'] = $stockCheck->stock+$qty;
                $stockCheck->update($data);
            }
        }

        $this->createStockHistory($item_id, $qty, $flag, $p_stock, $c_stock);
    }

    public function stockOut($item_id, $qty, $flag="Stock Out")
    {
        $stockCheck = Stock::where('item_id', $item_id)->first();
        $qty = create_float_format($qty);

        $data = [];
        $p_stock = $stockCheck->stock+$qty;
        $c_stock = $stockCheck->stock;

        $data['stock'] = $c_stock = create_float_format($c_stock);

        if(in_array($flag, ['delete purchase','delete initial']))
        {
            $data['stock'] = $stockCheck->stock-$qty;
            $stockCheck->update($data);
        }

        $this->createStockHistory($item_id, $qty, $flag, $p_stock, $c_stock);
    }


    public function stock_report($item_id, $qty, $type, $date)
    {
        $checkDate = db_date_format($date);

        $stockReport = StockReport::where('date', $checkDate)->where('item_id', $item_id)->first();

        $item = Item::find($item_id);
        $qty = create_float_format($qty, 2);


        $currentStock = StockReport::where('item_id', $item_id)->whereDate('date','<' ,$checkDate)->orderBy('date','DESC')->first();


        $openingStock = $currentStock ? $currentStock->opening_stock+$currentStock->purchase_qty-$currentStock->sale_qty+$currentStock->sale_return_qty-$currentStock->purchase_return_qty-$currentStock->loss_qty+$currentStock->stock_overflow_qty : 0;

        $stock = [];
        $stock['opening_stock'] = create_float_format($openingStock);
        if($stockReport == null) {
            $stock['item_id'] = $item_id;
            $stock['company_id'] = $item->company_id;
            $stock['product_code'] = $item->productCode;
            $stock['product_name'] = $item->item_name;
            $stock['date'] = $checkDate;
            $stock['ProductDateID'] = $item_id."(".$checkDate.")";
        }

        switch ($type)
        {
            case "purchase":
                $stock['purchase_qty'] = $stockReport ? $stockReport->purchase_qty+$qty : $qty;
                break;
            case "purchase return":
                $stock['purchase_return_qty'] = $stockReport ? $stockReport->purchase_return_qty+$qty : $qty;
                break;
            case "sale":
                $stock['sale_qty'] = $stockReport ? $stockReport->sale_qty+$qty : $qty;
                break;
            case "sale return":
                $stock['sale_return_qty'] = $stockReport ? $stockReport->sale_return_qty+$qty : $qty;
                break;
            case "delete sale":
                $stock['sale_qty'] = $stockReport ? $stockReport->sale_qty-$qty : 0;
                break;
            case "delete purchase":
                $stock['purchase_qty'] = $stockReport ? $stockReport->purchase_qty-$qty : 0;
                break;
            case "delete initial":
                $stock['opening_stock'] = $stockReport->opening_stock-$qty;
                break;
            case "initial":
                $stock['opening_stock'] = $qty;
                break;
            case "Lost":
                $stock['loss_qty'] = $qty;
                break;
            case "Overflow":
                $stock['stock_overflow_qty'] = $qty;
                break;
//            default:
//                $stock['sale_qty'] = 0;
//                $stock['purchase_qty'] = 0;
        }


        if(!$stockReport)
        {
            StockReport::create($stock);
        }else{
            $stockReport->update($stock);
        }

        $this->update_stock_report($item->id, $checkDate);
    }


    public function check_stock($item_id)
    {
        $stockCheck = Stock::where('item_id', $item_id)->first();

        if(!$stockCheck)
            return 0;
        else
            return create_float_format($stockCheck->stock);
    }


    public function update_stock_report($item_id="", $start_date="")
    {

        $stock_report = new StockReport();

        if( $item_id != "" && $start_date != "") {
            $stock_report = $stock_report->where('item_id', $item_id)->where('date',">=",$start_date);
        }else if($start_date != "") {
            $stock_report = $stock_report->where('date',">=",$start_date);
        }

        $stock_report = $stock_report->orderBy('item_id','asc')->orderBy('date','asc')->get();


        if(count($stock_report)==0)
        {

            if( $item_id != "") {

                $stock_report = StockReport::where('item_id', $item_id)->where('date',"<",$start_date)->orderBy('date','desc')->first();
                $stock_report = StockReport::where('item_id', $item_id)->where('date',">=",$stock_report->date)->orderBy('date','asc')->get();

            }else if($start_date != "") {
                $stock_items = StockReport::where('date',"<",$start_date)->groupBy('item_id')->pluck('item_id')->toArray();
//                dd($stock_items);
                $stock_report = [];
                foreach ($stock_items as $value)
                {
                    $stock_report[] = StockReport::where('item_id', $value)->where('date',"<",$start_date)->orderBy('date', 'desc')->first();
                }

            }
        }

        $item_id = "";
        foreach ($stock_report as $key => $value)
        {
            $item = Item::find($value->item_id);
            if($value->item_id != $item_id)
            {

                if($item_id>0)
                {
                    $stock = Stock::where('item_id', $item_id)->first();
                    $stock->stock = $closing_qty;
                    $stock->save();
                }

                $opening_qty = $value->opening_stock;
            }else{
                $opening_qty = $closing_qty;
            }

            $stock = StockReport::find($value->id);
            $item_id = $value->item_id;


            $sale_qty =  DB::table("sales")
                ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_details.item_id', $item_id)
                ->groupBy('sales_details.item_id')
                ->groupBy('sales.date')
                ->sum('sales_details.qty');

            $free_qty =  DB::table("sales")
                ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_details.item_id', $item_id)
                ->groupBy('sales_details.item_id')
                ->groupBy('sales.date')
                ->sum('sales_details.free');

            $sale_qty = $free_qty+$sale_qty;

            $purchase_qty =  DB::table("purchases")
                ->leftJoin('purchase_details', 'purchase_details.purchase_id', 'purchases.id')
                ->where('purchases.date', $value->date)
                ->where('purchase_details.item_id', $item_id)
                ->groupBy('purchase_details.item_id')
                ->groupBy('purchases.date')
                ->sum('purchase_details.qty');

            $sale_return_qty =  DB::table("sales")
                ->leftJoin('sales_return', 'sales_return.sale_id', 'sales.id')
                ->where('sales.date', $value->date)
                ->where('sales_return.item_id', $item_id)
                ->groupBy('sales_return.item_id')
                ->groupBy('sales.date')
                ->sum('sales_return.return_qty');

            $purchase_return_qty =  DB::table("purchases")
                ->leftJoin('return_purchases', 'return_purchases.purchase_id', 'purchases.id')
                ->where('purchases.date', $value->date)
                ->where('return_purchases.item_id', $item_id)
                ->groupBy('return_purchases.item_id')
                ->groupBy('purchases.date')
                ->sum('return_purchases.return_qty');

            $stock->opening_stock = $opening_qty;
            $stock->sale_qty = $sale_qty;
            $stock->purchase_qty =  $purchase_qty;
            $stock->sale_return_qty = $sale_return_qty;
            $stock->purchase_return_qty = $purchase_return_qty;


            if($stock->company_id==0)
                $stock->company_id = $item->company_id;


            $closing_qty = $opening_qty+$purchase_qty-$sale_qty+$sale_return_qty-$purchase_return_qty-$stock->loss_qty+$stock->stock_overflow_qty;

            $closing_qty = create_float_format($closing_qty);

            $stock->closing_qty = $closing_qty;
            $stock->update();


            if(count($stock_report)-1 == $key )
            {
                $stock = Stock::where('item_id', $item_id)->first();
                $stock->stock = $closing_qty;
                $stock->save();
            }
        }

    }


//    public function update_stock_report($item_id="", $start_date="")
//    {
////        if( $item_id != "" && $start_date != "") {
////
////            $endDate = Carbon::today();
////
////            $dataProcess =  DB::select('call ChangeStockReportByItem(?,?,?)',array($start_date, $endDate, $item_id));
////        }
//    }
}
