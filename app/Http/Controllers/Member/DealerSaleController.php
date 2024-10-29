<?php

namespace App\Http\Controllers\Member;

use App\DataTables\DealerSalesDataTable;
use App\Http\Services\SaleCommissionGenerate;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\DealerStockTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\Customer;
use App\Models\DealerStock;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\DealerSale;
use App\Models\DealerSaleDetails;
use App\Models\Sale;
use App\Models\SaleCommission;
use App\Models\SalesRequisition;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TrackShoppingBags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;

class DealerSaleController extends Controller
{
    use TransactionTrait, StockTrait, CompanyInfoTrait, DealerStockTrait;

    protected $print_system, $company_id, $authUser;

    public function __construct(){
//        $this->check_print_system();
        $this->authUser = Auth::user();
//        $this->company_id = Auth::user()->company_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DealerSalesDataTable $dataTable)
    {
        return $dataTable->render('member.dealer_sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query){
            $query->where('name','!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query){
            $query->where('name', 'shopping_bags');
        })->authCompany()->latest()->get();

        return view('member.dealer_sales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $saleDate = db_date_format($inputs['date']);

        $sale = [];
        $requisition_id = $inputs['requisition_id'];
        $sale['memo_no'] = $inputs['memo_no'];
        $sale['sale_code'] = sale_code_generate();
        $sale['chalan_no'] = $inputs['chalan_no'];
        $sale['customer_id'] = $customer_id = $inputs['customer_id'];

        $dealer = SalesRequisition::find($requisition_id);

        $created_by = $dealer->created_by;

        $sale['dealer_id'] = $dealer_id = $dealer->dealer_id;
        $sale['saler_id'] = $request->saler_id;
        $sale['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $sale['payment_method_id'] = $inputs['payment_method_id'];
        $sale['delivery_type_id'] = $inputs['delivery_type_id'];
        $sale['date'] = $saleDate;
        $sale['total_price'] = $inputs['sub_total'];
        $sale['discount_type'] = $inputs['discount_type'];
        $sale['discount'] = $inputs['discount'];
        $sale['total_discount'] = $inputs['discount'];
        $sale['paid_amount'] = $inputs['paid_amount'];
//        $sale['shipping_charge'] = $inputs['shipping_charge'];
        $sale['transport_cost'] = $inputs['transport_cost'];
        $sale['unload_cost'] = $inputs['unload_cost'] = $inputs['labor_cost'];
        $sale['total_amount'] = $inputs['total_amount'];
        $sale['amount_to_pay'] = $inputs['amount_to_pay'];
        $sale['notation'] = $inputs['notation'];
        $sale['membership_card'] = $inputs['membership_card'];
        $sale['vehicle_number'] = $inputs['vehicle_number'];

//        DB::beginTransaction();
//        try{

        $saleInsert = DealerSale::create($sale);

        $saleDetails = [];
        $saleDetails['sale_id'] = $saleInsert->id;

        $product = $request->product_id;
        $unit = $request->unit;
        $qty = $request->qty;
        $price = $request->price;
        $description = $request->description;
//        $free_qty = $request->free_qty;
//        $free = $request->free;
//        $pack_qty = $request->pack_qty;
//        $carton = $request->carton;
//        $last_sale_qty = $request->last_sale_qty;
        $available_stock = $request->available_stock;

        $total_amount = $insert = 0;
        $count_product = 0;
        $sale_product = $sale_product_qty = [];
        $saleLedgerDescription = "".$inputs['notation'];


        for($i=0; $i<count($product); $i++) {

            if (!isset($product[$i]) || !isset($qty[$i]))
                break;
            else
                $count_product++;


            if ($qty[$i] < 0 || $price[$i] < 0) {
                break;
            }


            $item = Item::find($product[$i]);
            $saleDetails['item_id'] = $item_id = $product[$i];
            $saleDetails['dealer_id'] = $dealer_id;
            $saleDetails['saler_id'] = $request->saler_id;
            $saleDetails['unit'] = $item->unit;
            $saleDetails['qty'] = $quantity = $qty[$i];
            $saleDetails['price'] = $price[$i];
            $saleDetails['total_price'] = $qty[$i] * $price[$i];
            $saleDetails['description'] = $description[$i];
            $saleDetails['free_qty'] = $item->free_qty ?? 0;
            $saleDetails['free'] = $free = $item->free_qty ? floor($quantity / $item->free_qty) : 0;
            $saleDetails['pack_qty'] = $item->pack_qty ?? 0;
            $saleDetails['carton'] = $item->pack_qty ? floor($quantity / $item->pack_qty) : 0;
            $saleDetails['trade_price'] = $item->trade_price;
//            $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
            $saleDetails['available_stock'] = $available_stock[$i];
            $saleDetails['company_id'] = Auth::user()->company_id;
            $saleDetails['date'] = $saleDate;

            $stock_out_qty = $free + $quantity;

            $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $price[$i] . ") & Free = " . $free : $saleLedgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price[$i] . ") Free = " . $free;

            if ($item->warranty > 0) {
                $saleDetails['warranty'] = $item->warranty;
                $saleDetails['warranty_start_date'] = $today = Carbon::today();
                $saleDetails['warranty_end_date'] = $today->addMonths($item->warranty);
            }


            $total_price = $qty[$i] * $price[$i];
            $total_amount += $total_price;

            $stockCheck = DealerStock::where('item_id', $item_id)->where('dealer_id', $dealer_id)->first();


            if (!$stockCheck)
                break;

            if (($stockCheck->stock < 0 || $stockCheck->stock < $quantity))
                break;


            $sale = DealerSaleDetails::create($saleDetails);

            if ($sale) {
                array_push($sale_product, $item_id);
                array_push($sale_product_qty, $quantity);
                $insert++;
            }

            /*
             *  TODO: Note Dealer And Stock out Process need full different.
             */


            if ($dealer_id > 0) {
                $this->dealerStockOut($item_id, $stock_out_qty, $dealer_id);
                $this->dealer_stock_report($item_id, $stock_out_qty, 'sale', $saleDate, $dealer_id);
            }

        }

        if($insert == $count_product)
        {

            $sale = [];
//            $sale['total_price'] = $total_amount;
            $sale['total_price'] = $total_amount;

            if($inputs['discount_type']=="fixed")
            {
                $discount = $inputs['discount'];
            }else{
                $discount = $total_amount*$inputs['discount']/100;
            }

            $sale['paid_amount'] = $inputs['paid_amount'];
            $sale['total_discount'] = $discount;
            $sale['amount_to_pay'] = $amount_to_pay = $total_amount-$discount+$inputs['transport_cost']+$inputs['unload_cost'];
            $sale['grand_total'] = $total_amount+$inputs['transport_cost']+$inputs['unload_cost'];
            $sale['due'] = $sale['amount_to_pay']-$inputs['paid_amount'];

            $saleInsert->update($sale);

            SalesRequisition::where('id', $requisition_id)->update(['is_sale'=>1, 'dealer_sale_id'=>$saleInsert->id]);

            $s = new SaleCommissionGenerate($saleInsert->id, $created_by, $requisition_id);
            $s->sale_commission_generate();

            $status = ['type' => 'success', 'message' => 'Dealer Sales done Successfully'];
        }else{

            for($i=0; $i<count($sale_product); $i++) {

                $this->dealer_stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate, $dealer_id);
                $this->dealerStockIn($sale_product[$i], $sale_product_qty[$i], $dealer_id, 'sale Return');
            }

            $status = ['type' => 'danger', 'message' => 'Dealer Sales product out of stock'];
            $saleInsert->delete();
        }


//        }catch (\Exception $e){
//
//            $status = ['type' => 'danger', 'message' => 'Unable to save'];
//            DB::rollBack();
//        }
//
//        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.dealer_sales.show', $saleInsert->id)->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['sales'] = $sale = DealerSale::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $sale->sale_code;
        $data['sale_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        return view('member.dealer_sales.invoice_show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_sale($id)
    {
        $this->check_print_system();
        $data['sales'] = $sale = DealerSale::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $sale->sale_code;
        $data['sale_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        return view('member.dealer_sales.'.$this->print_system.'_print_sales', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = DealerSale::findOrFail($id);

        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = Customer::authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query){
            $query->where('name','!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query){
            $query->where('name', 'shopping_bags');
        })->authCompany()->select('*', 'items.id as item_id')->latest()->get();


        return view('member.dealer_sales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $saleFind = DealerSale::findOrFail($id);

        $inputs = $request->all();

        $dealer_id = $saleFind->dealer_id;

        $saleCommission =  SaleCommission::where('sale_id', $id)->first();

        $sale = [];
        $sale['memo_no'] = $inputs['memo_no'];
        $sale['chalan_no'] = $inputs['chalan_no'];
        $sale['customer_id'] = $inputs['customer_id'];
        $sale['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $sale['payment_method_id'] = $inputs['payment_method_id'];
        $sale['delivery_type_id'] = $inputs['delivery_type_id'];
        $sale['date'] = $saleDate = db_date_format($inputs['date']);
        $sale['total_price'] = $inputs['sub_total'];
        $sale['discount_type'] = $inputs['discount_type'];
        $sale['discount'] = $inputs['discount'];
        $sale['total_discount'] = $inputs['discount'];
        $sale['paid_amount'] = $inputs['paid_amount'];
//        $sale['shipping_charge'] = $inputs['shipping_charge'];
        $sale['unload_cost'] = $inputs['unload_cost'] = $inputs['labor_cost'];
        $sale['transport_cost'] = $inputs['transport_cost'];
        $sale['total_amount'] = $inputs['grand_total'];
        $sale['amount_to_pay'] = $inputs['amount_to_pay'];
        $sale['notation'] = $inputs['notation'];
        $sale['membership_card'] = $inputs['membership_card'];
        $sale['vehicle_number'] = $inputs['vehicle_number'];

        DB::beginTransaction();
        try{

            $saleFind->update($sale);

            $saleDetails = [];
            $saleDetails['sale_id'] = $saleFind->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $total_price = $request->total_price;
            $description = $request->description;
            $available_stock = $request->available_stock;
//          $last_sale_qty = $request->last_sale_qty;
            $sale_details_id = $request->sale_details_id;


            $deleteDealerSaleDetails = DealerSaleDetails::where('sale_id', $id)->whereNotIn('id', $sale_details_id)->get();

            foreach ($deleteDealerSaleDetails as $value)
            {
                $sale_detail_id = DealerSaleDetails::find($value->id);
                $sale_detail_id->sale_status = "edit";
                $sale_detail_id->update();

                $this->dealer_stock_report($value->item_id, $sale_detail_id->qty, 'delete sale', $saleDate, $dealer_id);
                $this->dealerStockIn($value->item_id, $sale_detail_id->qty, $dealer_id, 'delete sale');
                $value->delete();
            }


            $total_amount = $insert = 0;
            $count_product = 0;
            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = "".$inputs['notation'];
            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;
                else
                    $count_product++;


                if($qty[$i] <= 0 || $total_price[$i] <= 0 )
                {
                    break;
                }


                if($sale_details_id[$i] != "new"){
                    $sale_detail_id = DealerSaleDetails::find($sale_details_id[$i]);

                    $this->dealer_stock_report($sale_detail_id->item_id, $sale_detail_id->qty, 'delete sale', $saleDate, $dealer_id);
                    $this->dealerStockIn($sale_detail_id->item_id, $sale_detail_id->qty, $dealer_id, 'delete sale');
                }else{
                    $saleDetails['sale_id'] = $saleFind->id;
                }

                $item = Item::find($product[$i]);
                $saleDetails['item_id'] = $item_id = $product[$i];
                $saleDetails['unit'] = $item->unit;
                $saleDetails['qty'] = $quantity = $qty[$i];
                $saleDetails['total_price'] = $total_price[$i];
                $saleDetails['description'] = $description[$i];
//              $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
                $saleDetails['free_qty'] = $item->free_qty;
                $saleDetails['free'] = $free = floor($quantity/$item->free_qty);
                $saleDetails['pack_qty'] = $item->pack_qty;
                $saleDetails['carton'] =  floor($quantity/$item->pack_qty);
                $saleDetails['trade_price'] =  $item->trade_price;
                $saleDetails['available_stock'] = $available_stock[$i];
                $saleDetails['company_id'] = Auth::user()->company_id;
                $saleDetails['date'] = $saleDate;

                $stock_out_qty = $free+$quantity;

                if($item->warranty>0){
                    $saleDetails['warranty'] = $item->warranty;
                    $saleDetails['warranty_start_date'] = $today = Carbon::today();
                    $saleDetails['warranty_end_date'] = $today->addMonths($item->warranty);
                }


                $price = $total_price[$i]/$qty[$i];
                $saleDetails['price'] = create_float_format($price, 3);
                $total_amount +=$total_price[$i];


                $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name."(".$quantity.$item->unit."x".$price.") & Free = ".$free : $saleLedgerDescription." & ".$item->item_name."(".$quantity.$item->unit."x".$price.") & Free = ".$free;


                $stockCheck = Stock::where('item_id', $item_id)->first();
                if($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                    break;

                if($sale_details_id[$i] != "new") {

                    $sale = $sale_detail_id->update($saleDetails);
                    $insert++;
                }else{
                    $sale = DealerSaleDetails::create($saleDetails);

                    if($sale)
                    {
                        array_push($sale_product, $item_id);
                        array_push($sale_product_qty, $quantity);
                        $insert++;
                    }
                }


                $this->dealerStockOut($item_id, $stock_out_qty, $dealer_id);
                $this->dealer_stock_report($item_id, $stock_out_qty, 'sale', $saleDate, $dealer_id);



            }


            if($insert == $count_product)
            {
                $bags = Item::whereHas('category', function ($query){
                    $query->where('name', 'shopping_bags');
                })->latest()->get();

                $trackBags = TrackShoppingBags::where('sale_id', $id)->get();
//            dd($trackBags);
                foreach ($trackBags as $bag)
                {
                    $this->dealer_stock_report($bag->bag_id, $bag->qty, 'sale return', $saleDate, $dealer_id);
                    $this->dealerStockIn($bag->bag_id, $bag->qty, $dealer_id, 'sale return');
                    $bag->delete();
                }

                foreach ($bags as $value)
                {
                    if(!empty($inputs['shopping_bags_'.$value->id]))
                    {
                        $saleBag = [];
                        $saleBag['sale_id'] = $saleFind->id;
                        $saleBag['bag_id'] = $value->id;
                        $saleBag['qty'] = $qt = $inputs['shopping_bags_'.$value->id];
                        TrackShoppingBags::create($saleBag);

                        $this->dealer_stock_report($value->id,  $qt, 'sale', $saleDate, $dealer_id);
                        $this->dealerStockOut($value->id, $qt, $dealer_id,'DealerSale Edit');

                    }

                }

                $sale = [];
//            $sale['total_price'] = $total_amount;
                $sale['total_price'] = $total_amount;

                if($inputs['discount_type']=="fixed")
                {
                    $discount = $inputs['discount'];
                }else{
                    $discount = $total_amount*$inputs['discount']/100;
                }

                $sale['paid_amount'] = $inputs['paid_amount'];
                $sale['total_discount'] = $discount;
                $sale['amount_to_pay'] = $total_amount-$discount+$inputs['unload_cost']+$inputs['transport_cost'];
                $sale['grand_total'] = $total_amount+$inputs['unload_cost']+$inputs['transport_cost'];
                $sale['due'] = $sale['amount_to_pay']-$inputs['paid_amount'];

                $saleFind->update($sale);

                SaleCommission::where('id', $saleCommission->id)
                    ->where('employee_id', $saleCommission->employee_id)
                    ->where('requisition_id', $saleCommission->requisition_id)
                    ->delete();

                $s = new SaleCommissionGenerate($saleFind->id, $saleCommission->employee_id, $saleCommission->requisition_id);
                $s->sale_commission_generate();


                $status = ['type' => 'success', 'message' => 'Dealer Sales update Successfully'];
            }
            else{

                for($i=0; $i<count($sale_product); $i++) {

                    $this->dealer_stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate, $dealer_id);
                    $this->dealerStockIn($sale_product[$i], $sale_product_qty[$i], $dealer_id, 'sale Return');
                }
                $status = ['type' => 'danger', 'message' => 'Dealer Sales product out of stock'];
            }


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.dealer_sales.show', $saleFind->id)->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = DealerSale::findOrFail($id);

        if(!$sale)
        {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }

        foreach($sale->dealer_sale_details as $value)
        {
            $this->dealer_stock_report($value->item_id, $value->qty, 'delete sale', $sale->date);
            $this->dealerStockIn($value->item_id, $value->qty,'delete sale');
        }

        $sale->dealer_sale_details()->delete();
        $sale->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    private function check_print_system()
    {
        $settings = Setting::where('key', '=', 'print_page_setup')->where('company_id', $this->company_id)->first();

        if($settings)
        {
            $this->print_system = $settings->value;
        }else{
            $this->print_system = '';
        }

        $this->print_system = $this->print_system == "default" ? '' : $this->print_system;
    }

}
