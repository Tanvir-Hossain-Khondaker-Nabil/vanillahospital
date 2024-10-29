<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\PosSaleRequest;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TrackShoppingBags;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PosSaleController extends Controller
{
    use TransactionTrait, StockTrait, CompanyInfoTrait;

    public function create()
    {
        if(!Auth::user()->company_id)
        {
            $status = ['type' => 'danger', 'message' => 'User is not Assigned by Company '];
            return redirect()->back()->with('status', $status);
        }

        $settings = new Setting();
        $settings = $settings->where('company_id', Auth::user()->company_id);

        foreach ($settings->get() as $setting) {
            Config::set('settings.'.$setting->key, $setting->value);
        }


        $lastId = Sale::latest()->pluck('id')->first();
        $data['memo_no'] = memo_generate("Inv-", $lastId+1);
        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();

        $data['products'] = Item::whereHas('category', function ($query){
            $query->where('name','!=', 'shopping_bags');
        })->whereHas('stock_details', function ($query){
            $query->where('stock','>', 0);
        })->authCompany()->orderAsc()->latest()->limit(16)->get();

        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query){
            $query->where('name', 'shopping_bags');
        })->authCompany()->latest()->get();

        return view('member.sales.pos_sale_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PosSaleRequest $request)
    {

        // Retrieve the validated input data...
        $validated = $request->validated();

        $inputs = $request->all();

        $saleDate = db_date_format($inputs['date']);

        $sale = [];
        $sale['sale_code'] = sale_code_generate();
        $sale['customer_id'] = $inputs['customer_id'] ?? null;
        $sale['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $sale['payment_method_id'] = $inputs['payment_method_id'];
        $sale['delivery_type_id'] = null;
        $sale['date'] = $saleDate;
        $sale['total_price'] = $inputs['sub_total'];
        $sale['discount_type'] = $inputs['discount_type'];
        $sale['discount'] = $inputs['discount'] ?? 0;
        $sale['total_discount'] = $inputs['discount'] ?? 0;
        $sale['paid_amount'] = $inputs['paid_amount'];
        $sale['shipping_charge'] = $inputs['shipping_charge'] ?? 0;
        $sale['total_amount'] = $inputs['total_amount'];
        $sale['amount_to_pay'] = $inputs['amount_to_pay'];
        $sale['notation'] = $inputs['notation'];
        $sale['track_sale'] = "pos";


        DB::beginTransaction();
        try{

            $saleInsert = Sale::create($sale);

            $saleDetails = [];
            $saleDetails['sale_id'] = $saleInsert->id;

            $product = $request->product_id;
            $qty = $request->qty;
            $price = $request->price;
            $total_price = $request->total_price;

            $total_amount = $insert = 0;
            $count_product = 0;
            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = "";


            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;
                else
                    $count_product++;


                if($qty[$i] < 0 || $price[$i] < 0 )
                {
                    break;
                }


                $item = Item::find($product[$i]);
                $saleDetails['item_id'] = $item_id = $product[$i];
                $saleDetails['unit'] = $item->unit;


                if(config('settings.price_system') == "total_price_price_qty")
                {

                    $saleDetails['total_price'] = $total_price[$i];
                    $saleDetails['price'] = $price[$i];
                    $saleDetails['qty'] = $quantity = create_float_format($total_price[$i]/$price[$i]);

                }else if( config('settings.price_system') == "total_price_qty_price"){

                    $saleDetails['total_price'] = $total_price[$i];
                    $saleDetails['qty'] = $quantity = $qty[$i];
                    $saleDetails['price'] = create_float_format($total_price[$i]/$qty[$i]);

                }else {

                    $saleDetails['qty'] = $quantity = $qty[$i];
                    $saleDetails['price'] = $price[$i];
                    $saleDetails['total_price'] = create_float_format($qty[$i]*$price[$i]);

                }

                $saleDetails['company_id'] = Auth::user()->company_id;

                $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name."(".$quantity.$item->unit.")" : $saleLedgerDescription." & ".$item->item_name."(".$quantity.$item->unit.")";

                if($item->warranty>0){
                    $saleDetails['warranty'] = $item->warranty;
                    $saleDetails['warranty_start_date'] =  Carbon::today();
                    $saleDetails['warranty_end_date'] = Carbon::today()->addMonths($item->warranty);
                }

                if($item->gurrantee>0){
                    $saleDetails['gurrantee'] = $item->gurrantee;
                    $saleDetails['gurrantee_start_date'] =  Carbon::today();
                    $saleDetails['gurrantee_end_date'] = Carbon::today()->addMonths($item->gurrantee);
                }

                $saleDetails['date'] = $saleDate;

//            $total_price = $qty[$i]*$price[$i];
                $sale_total_price = $saleDetails['total_price'];
                $total_amount += $sale_total_price;

                $stockCheck = Stock::where('item_id', $item_id)->first();
                if($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                    break;

                $sale_list = SaleDetails::create($saleDetails);

                if($sale_list)
                {
                    array_push($sale_product, $item_id);
                    array_push($sale_product_qty, $quantity);
                    $insert++;
                }

                $this->stock_report($product[$i], $qty[$i], 'sale', $saleDate);
                $this->stockOut($product[$i], $qty[$i]);

            }


            if($insert == $count_product)
            {
                $bags = Item::whereHas('category', function ($query){
                    $query->where('name', 'shopping_bags');
                })->latest()->get();

                foreach ($bags as $value)
                {
                    if(!empty($inputs['shopping_bags_'.$value->id]))
                    {
                        $saleBag = [];
                        $saleBag['sale_id'] = $saleInsert->id;
                        $saleBag['bag_id'] = $value->id;
                        $saleBag['qty'] = $qt = $inputs['shopping_bags_'.$value->id];
                        TrackShoppingBags::create($saleBag);

                        $this->stock_report($value->id,  $qt, 'sale', $saleDate);
                        $this->stockOut($value->id, $qt);
                        //  $this->createStockHistory($value->id, $qt, 'Sale', $p_stock, $c_stock);
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
                $sale['amount_to_pay'] = $amount_to_pay = $total_amount-$discount+$inputs['shipping_charge'];
                $sale['grand_total'] = $total_amount+$inputs['shipping_charge'];
                $sale['due'] = $sale['amount_to_pay']-$sale['paid_amount'];


                $saleInsert->update($sale);

                $inputs['transaction_code'] = transaction_code_generate();

                $save_transaction = new Transactions();
                $save_transaction->transaction_code = $inputs['transaction_code'];
                $save_transaction->supplier_id = $inputs['customer_id'];
                $save_transaction->sale_id = $saleInsert->id;
                $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
                $save_transaction->date = $saleDate;
                $save_transaction->amount = $total_amount;
                $save_transaction->notation = $inputs['notation'];
                $save_transaction->transaction_method = $inputs['transaction_method'] = "Sales";
                $save_transaction->save();

                $account = CashOrBankAccount::find($request->cash_or_bank_id);

                if(isset($request->customer_id)) {
                    $sharer = SupplierOrCustomer::find($request->customer_id);
                    $sharer->name = $request->customer_name;
                    $sharer->save();
                    $inputs['sharer_name'] = $sharer ? $sharer->name : '';
                }else{
                    $sharer = null;
                    $inputs['sharer_name'] = '';
                }

                // Update Cash and Bank Account Balance
                $this->bankAccountBalanceUpdate($inputs['transaction_method'], $account, $inputs['paid_amount']);


                if(isset($request->customer_id)) {
                    $this->sharerBalanceUpdate($inputs['transaction_method'], $sharer, $inputs['paid_amount']);
                }

                $this->create_sale_transaction($account, $save_transaction, $saleInsert, $discount, $sale['due'], $sharer, $total_amount, $inputs, $saleLedgerDescription);


                /*
                 * TODO: Create Transaction History for Sales
                 */

                $status = ['type' => 'success', 'message' => trans('sale.sales_done_successfully')];
            }else{

                for($i=0; $i<count($sale_product); $i++) {

                    $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                    $this->stockIn($sale_product[$i], $sale_product_qty[$i],'sale Return');
                    //  $this->createStockHistory($sale_product[$i], $sale_product_qty[$i], 'Stock In', $p_stock, $c_stock);
                }

                $status = ['type' => 'danger', 'message' => trans('sale.sales_product_out_of_stock')];
                $saleInsert->delete();
            }


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('sale.unable_to_save')];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.sales.print_sale', $saleInsert->id);
        }else{

            return redirect()->back()->with('status', $status);
        }


    }


}
