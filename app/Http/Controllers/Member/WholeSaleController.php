<?php

namespace App\Http\Controllers\Member;

use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TrackShoppingBags;
use App\Models\Transactions;
use App\Services\PurchaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WholeSaleController extends SalesController
{


    // ------------ Whole Selling process ---------------


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function wholeSaleCreate()
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

        return view('member.sales.whole_sale_create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWholeSale(Request $request)
    {
        $inputs = $request->all();

        $sale = [];
        $sale['memo_no'] = $inputs['memo_no'];
        $sale['sale_code'] = sale_code_generate();
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
        $sale['transport_cost'] = $inputs['transport_cost'];
        $sale['unload_cost'] = $inputs['unload_cost'] = $inputs['labor_cost'];
        $sale['total_amount'] = $inputs['total_amount'];
        $sale['amount_to_pay'] = $inputs['amount_to_pay'];
        $sale['notation'] = $inputs['notation'];
        $sale['membership_card'] = $inputs['membership_card'];
        $sale['track_sale'] = "whole";

        DB::beginTransaction();
        try{

            $saleInsert = Sale::create($sale);

            $saleDetails = [];
            $saleDetails['sale_id'] = $saleInsert->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $price = $request->price;
            $total_price = $request->total_price;
            $description = $request->description;
            $last_sale_qty = $request->last_sale_qty;
            $available_stock = $request->available_stock;


            $total_amount = $insert = 0;
            $count_product = 0;
            $sale_product = $sale_product_qty = [];

            $saleLedgerDescription = "";
            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;
                else
                    $count_product++;


                if($qty[$i] < 0 || $total_price[$i] < 0 )
                {
                    break;
                }


                $item = Item::find($product[$i]);
                $saleDetails['item_id'] = $item_id = $product[$i];
                $saleDetails['unit'] = $item->unit;
                $saleDetails['qty'] = $quantity = $qty[$i];
                $saleDetails['total_price'] = $total_price[$i];
//                $saleDetails['description'] = $description[$i];
                $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
                $saleDetails['available_stock'] = $available_stock[$i];
                $saleDetails['company_id'] = Auth::user()->company_id;
                $saleDetails['date'] = $saleDate;

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


                $purchaseService = new PurchaseService();
                $purchasePrice = $purchaseService->averagePurchasePrice($item_id);
                $saleDetails['purchase_price'] = $purchasePrice;

                $price = $total_price[$i]/$qty[$i];
                $saleDetails['price'] = create_float_format($price, 3);
                $total_amount +=$total_price[$i];

                $stockCheck = Stock::where('item_id', $item_id)->first();

                if($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                    break;

                $sale = SaleDetails::create($saleDetails);


                $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name."(".$quantity.$item->unit."x".$price.")" : $saleLedgerDescription." & ".$item->item_name."(".$quantity.$item->unit."x".$price.")";



                if($sale)
                {
                    array_push($sale_product, $item_id);
                    array_push($sale_product_qty, $quantity);
                    $insert++;
                }

                $this->stock_report($product[$i], $qty[$i], 'sale', $saleDate);
                $this->stockOut($product[$i], $qty[$i]);
                //  $this->createStockHistory($product[$i], $qty[$i], 'Stock Out', $p_stock, $c_stock);

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
            //$this->createStockHistory($value->id, $qt, 'Sale', $p_stock, $c_stock);
                    }

                }

                $sale = [];
//              $sale['total_price'] = $total_amount;
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

            $status = ['type' => 'danger', F => trans('sale.unable_to_save')];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.sales.show', $saleInsert->id)->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }


    }

}
