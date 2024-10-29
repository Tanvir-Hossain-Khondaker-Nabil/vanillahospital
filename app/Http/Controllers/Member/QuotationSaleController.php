<?php

namespace App\Http\Controllers\Member;

use App\Http\Services\CustomerSave;
use App\Http\Services\SaleTransaction;
use App\Http\Traits\StockTrait;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\PaymentMethod;
use App\Models\Quotation;
use App\Models\QuotationCompany;
use App\Models\QuotationDetail;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\Transactions;
use App\Services\PurchaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationSaleController extends Controller
{

    use StockTrait;

    public function create(Request $request){

        $id = $request->id;

        $data['model'] = $quotation = Quotation::findOrFail($id);

        $quotationer = QuotationCompany::find($quotation->quotationer_id);

        if(!$quotationer->customer_id)
        {
            $customerSave = new CustomerSave();
            $customer = $customerSave->create_customer($quotationer);
            $quotationer->customer_id = $customer->id;
            $quotationer->save();

            $data['customer_id'] = $customer->id;

        }else{
            $data['customer_id'] = $quotationer->customer_id;
        }

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


        return view('member.quotation_sales.create', $data);

    }


    public function store(Request $request)
    {


        $inputs = $request->all();

        $saleDate = db_date_format($inputs['date']);

        $quotation_id = $inputs['quotation_id'];
        $sale = [];
        $sale['memo_no'] = $inputs['memo_no'];
        $sale['sale_code'] = sale_code_generate();
        $sale['chalan_no'] = $inputs['chalan_no'];
        $sale['customer_id'] = $inputs['customer_id'];
        $sale['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $sale['payment_method_id'] = $inputs['payment_method_id'];
        $sale['delivery_type_id'] = $inputs['delivery_type_id'];
        $sale['date'] = $saleDate;
        $sale['total_price'] = $inputs['sub_total'];
        $sale['discount_type'] = isset($inputs['discount_type']) ? $inputs['discount_type'] : "Fixed";
        $sale['discount'] = $inputs['discount'] ?? 0;
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

//         DB::beginTransaction();
//         try{

        $saleInsert = Sale::create($sale);

        $saleDetails = [];
        $saleDetails['sale_id'] = $saleInsert->id;

        $product = $request->product_id;
        $unit = $request->unit;
        $qty = $request->qty;
        $price = $request->price;
        $available_stock = $request->available_stock;
        $quotation_details_id = $request->quotation_detail_id;
//        $per_discount = $request->per_discount;


        $total_amount = $total_discount = $insert = 0;
        $count_product = 0;
        $sale_product = $sale_product_qty = [];
        $saleLedgerDescription = "".$inputs['notation'];
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
            $saleDetails['qty'] = $quantity = $qty[$i];
            $saleDetails['price'] = $price[$i];
            $saleDetails['total_price'] = create_float_format(($qty[$i]*$price[$i]));
//            $saleDetails['description'] = $description[$i];
//            $saleDetails['discount'] = $per_discount[$i];
//            $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
            $saleDetails['available_stock'] = $available_stock[$i];
            $saleDetails['company_id'] = Auth::user()->company_id;
            $saleDetails['date'] = $saleDate;

            $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name."(".$quantity.$item->unit."x".$price[$i].")" : $saleLedgerDescription." & ".$item->item_name."(".$quantity.$item->unit."x".$price[$i].")";


            if($item->warranty>0){
                $saleDetails['warranty'] = $item->warranty;
                $saleDetails['warranty_start_date'] =  Carbon::today();
                $saleDetails['warranty_end_date'] = Carbon::today()->addMonths($item->warranty);
            }


            $total_price = create_float_format(($qty[$i]*$price[$i]));
            $total_amount += $total_price;
//            $total_discount += $per_discount[$i];

            $stockCheck = Stock::where('item_id', $item_id)->first();
            if($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                break;


            $purchaseService = new PurchaseService();
            $purchasePrice = $purchaseService->averagePurchasePrice($item_id);
            $saleDetails['purchase_price'] = $purchasePrice;

            if(config('settings.sale_profit_by_quotation'))
            {
                $quote_purchase_price = $purchaseService->purchasePriceByQuotation($quotation_id, $item_id);
                $saleDetails['quote_purchase_price'] = $quote_purchase_price;
            }

            $sale = SaleDetails::create($saleDetails);


            if($quotation_id>0 && isset($quotation_details_id[$i]))
            {
                $quotationDetailId = $quotation_details_id[$i];
                $quotationDetail = QuotationDetail::find($quotationDetailId);
                $quotationDetail->sale_qty = $quantity+$quotationDetail->sale_qty ;
                $quotationDetail->save();
            }


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

            $sale = [];
//            $sale['total_price'] = $total_amount;
            $sale['total_price'] = $total_amount;

            if($inputs['discount_type']=="fixed")
            {
            $discount = $inputs['discount'];
            }else{
                $discount = ($total_amount*$inputs['discount']/100);
                $discount = $discount>0 ? $discount+$total_discount : $total_discount;
            }

            $sale['paid_amount'] = $inputs['paid_amount'];
            $sale['total_discount'] = $discount;
            $sale['amount_to_pay'] = $amount_to_pay = $total_amount-$discount+$inputs['transport_cost']+$inputs['unload_cost'];
            $sale['grand_total'] = $total_amount+$inputs['transport_cost']+$inputs['unload_cost'];
            $sale['due'] = $sale['amount_to_pay']-$inputs['paid_amount'];

            $sale['quotation_id'] = $quotation_id;
            $saleInsert->update($sale);

            if($quotation_id)
            {
                $quotation = Quotation::find($quotation_id);
                $quotation->sale_id = $quotation->sale_id != "" ? $quotation->sale_id.",".$saleInsert->id : $saleInsert->id;
                $quotation->save();

                $inputs['notation'] = "Ref# ".$quotation->ref.", ".$inputs['notation'];
            }

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

            $transaction = new SaleTransaction($saleInsert->id);
            $transaction->updateTransactions();


            $status = ['type' => 'success', 'message' => 'Sales done Successfully'];

        }else{

            for($i=0; $i<count($sale_product); $i++) {

                $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                $this->stockIn($sale_product[$i], $sale_product_qty[$i],'sale Return');
            }

            $status = ['type' => 'danger', 'message' => 'Sales product out of stock'];
            $saleInsert->delete();
        }


//         }catch (\Exception $e){
//
//             $status = ['type' => 'danger', 'message' => 'Unable to save'];
//             DB::rollBack();
//         }
//
//         DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.sales.show', $saleInsert->id)->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }

    }



}
