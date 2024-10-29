<?php

namespace App\Http\Controllers\Member;

use App\Http\Services\CustomerSave;
use App\Http\Services\PurchaseTransaction;
use App\Http\Services\SaleTransaction;
use App\Http\Traits\StockTrait;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Quotation;
use App\Models\QuotationCompany;
use App\Models\QuotationDetail;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\SupplierPurchases;
use App\Models\Transactions;
use App\Services\PurchaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationPurchaseController extends Controller
{

    use StockTrait;

    public function create(Request $request){

        $id = $request->id;
        $multi_supplier = $data['multi_supplier'] = $request->multi_supplier;

        $lastId = Purchase::latest()->pluck('id')->first();
        $data['modal'] = $quotation = Quotation::findOrFail($id);


        $data['memo_no'] = $data['chalan_no'] = memo_generate("MC-", $lastId+1);
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->authCompany()->latest()->pluck('name', 'id');
        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');
        $data['purchase_products'] = [];
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        if($multi_supplier)
        {

            return view('member.quotation_purchases.multi_supplier_purchase_create', $data);
        }else{

            return view('member.quotation_purchases.create', $data);
        }

    }


    public function store(Request $request)
    {


        $inputs = $request->all();

        $purchase = [];
        $purchase['memo_no'] = $inputs['memo_no'];
        $purchase['chalan'] = isset($inputs['chalan']) ? $inputs['chalan'] : $inputs['memo_no'];
        $purchase['supplier_id'] = $inputs['supplier_id'];
        $purchase['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $purchase['payment_method_id'] = $inputs['payment_method_id'];
        $purchase['date'] = $purchaseDate = db_date_format($inputs['date']);
        $purchase['total_price'] = $inputs['total_bill'];
        $purchase['transport_cost'] = $inputs['transport_cost'];
        $purchase['unload_cost'] = $inputs['unload_cost'];
        $purchase['bank_charge'] = $inputs['bank_charge'];
        $purchase['discount_type'] = $inputs['discount_type'];
        $purchase['discount'] = $inputs['discount'];
        $purchase['total_discount'] = $inputs['discount'];
        $purchase['due_amount'] = 0;
        $purchase['advance_amount'] = 0;
        $purchase['paid_amount'] = $inputs['paid_amount'];
        $purchase['total_amount'] = $inputs['total_amount'];
        $purchase['amt_to_pay'] = $inputs['amount_to_pay'];
        $purchase['notation'] = $inputs['notation'];
        $purchase['invoice_no'] = $inputs['invoice_no'];
        $purchase['vehicle_number'] = $inputs['vehicle_number'];
        $purchase['is_requisition'] = isset($inputs['is_requisition']) ? $inputs['is_requisition'] : 0;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = $this->fileUpload($file, '/file/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $purchase['file'] = $upload;

        }

        DB::beginTransaction();
        try{

            $purchaseId = Purchase::create($purchase);

            $purchaseDetails = [];
            $purchaseDetails['purchase_id'] = $purchaseId->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $price = $request->price;
            $total_price = $request->total_price;
            $description = $request->description;
            $quotation_details_id = $request->quotation_details_id;

            $ledgerDescription = "".$inputs['notation'];

            $total_amount = 0;
            for($i=0; $i<count($product); $i++){

                if(!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if($qty[$i] < 1 )
                {
                    break;
                }


                $item = Item::find($product[$i]);
                $purchaseDetails['item_id'] = $item_id = $product[$i];
                $purchaseDetails['unit'] = $item->unit;
                $purchaseDetails['qty'] = $quantity = $qty[$i];
//            $purchaseDetails['price'] = $price[$i];
//            $purchaseDetails['total_price'] = $qty[$i]*$price[$i];
                $purchaseDetails['total_price'] = $total_price[$i];
                $purchaseDetails['price'] = $price = create_float_format($total_price[$i]/$qty[$i],2);
                $purchaseDetails['description'] = $description[$i];
                $purchaseDetails['company_id'] = Auth::user()->company_id;
                $purchaseDetails['date'] = $purchaseDate;

                $ledgerDescription = empty($ledgerDescription) ? $item->item_name."(".$quantity.$item->unit.")" : $ledgerDescription." & ".$item->item_name."(".$quantity.$item->unit."x".$price.")" ;


                if($item->warranty>0){
                    $purchaseDetails['warranty'] = $item->warranty;
                    $purchaseDetails['warranty_start_date'] = $today = Carbon::today();
                    $purchaseDetails['warranty_end_date'] = $today->addMonths($item->warranty);
                }


//            $total_price = $qty[$i]*$price[$i];
                $total_amount += $total_price[$i];

                PurchaseDetail::create($purchaseDetails);

                $data = [];
                $data['item_id'] = $item_id;
                $data['qty'] = $quantity;
                $data['supplier_id'] = $inputs['supplier_id'];
                SupplierPurchases::create($data);

                if($inputs['quotation_id'])
                {
                    $quotation_detail_id =$quotation_details_id[$i];
                    $quotationDetail = QuotationDetail::find($quotation_detail_id);
                    $quotationDetail->purchase_qty = $qty[$i]+$quotationDetail->purchase_qty ;
                    $quotationDetail->save();
                }

                $this->stock_report($product[$i], $qty[$i], 'purchase', $purchaseDate);
                $this->stockIn($product[$i], $qty[$i]);

            }


            $purchase = [];
            $purchase['total_price'] = $total_amount;
            $purchase['total_amount'] = $total_amount+ $inputs['transport_cost']+ $inputs['unload_cost']+ $inputs['bank_charge'];

            if($inputs['discount_type']=="Fixed")
            {
                $discount = $inputs['discount'];
            }else{
                $discount = $total_amount*$inputs['discount']/100;
            }

            $purchase['paid_amount'] = $inputs['paid_amount'];
            $purchase['total_discount'] = $discount;
            $purchase['amt_to_pay'] = $purchase['total_amount']-$discount;
            $lastAmount = $purchase['amt_to_pay']-$inputs['paid_amount'];

            $purchase['advance_amount'] = $purchase['due_amount'] = 0;
            if($lastAmount<0)
                $purchase['advance_amount'] = (-1)*$lastAmount;
            else
                $purchase['due_amount'] = $lastAmount;

            $purchase['quotation_id'] = $quotation_id = $inputs['quotation_id'];

            $purchaseId->update($purchase);


            if($quotation_id)
            {
                $quotation = Quotation::find($quotation_id);
                $quotation->purchase_id = $quotation->purchase_id != "" ? $quotation->purchase_id.",".$purchaseId->id : $purchaseId->id;
                $quotation->save();

                $inputs['notation'] = "Ref# ".$quotation->ref.", ".$inputs['notation'];
            }


            $inputs['transaction_code'] = transaction_code_generate();

            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->supplier_id = $inputs['supplier_id'];
            $save_transaction->purchase_id = $purchaseId->id;
            $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
            $save_transaction->date = $purchaseDate;
            $save_transaction->amount = $purchase['amt_to_pay'];
            $save_transaction->notation = $inputs['notation'];
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Purchases";
            $save_transaction->save();

            $purchase = new PurchaseTransaction($purchaseId->id);
            $purchase->updateTransactions();


            $status = ['type' => 'success', 'message' => 'Purchase Done Successfully'];


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.purchase.show', $purchaseId->id)->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }


    }



    public function multi_supplier_based_store(Request $request)
    {

        $inputs = $request->all();

        $suppliers = array_unique($request->supplier_id);

        $purchaseItemsSupplier = [];

        foreach ($request->supplier_id as $key => $value)
        {
            $purchaseItemsSupplier[$value][]=$key;
        }


        $product = $request->product_id;
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $request->total_price;
        $description = $request->description;
        $quotation_details_id = $request->quotation_details_id;

        foreach ($purchaseItemsSupplier as $supplier => $value)
        {
            $purchase = [];
            $purchase['memo_no'] = $inputs['memo_no'];
            $purchase['chalan'] = isset($inputs['chalan']) ? $inputs['chalan'] : $inputs['memo_no'];
            $purchase['supplier_id'] = $supplier;
            $purchase['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
            $purchase['payment_method_id'] = $inputs['payment_method_id'];
            $purchase['date'] = $purchaseDate = db_date_format($inputs['date']);
            $purchase['total_price'] = 0;
            $purchase['transport_cost'] = 0;
            $purchase['unload_cost'] = 0;
            $purchase['bank_charge'] = 0;
            $purchase['discount_type'] = "fixed";
            $purchase['discount'] = 0;
            $purchase['total_discount'] = 0;
            $purchase['due_amount'] = 0;
            $purchase['advance_amount'] = 0;
            $purchase['paid_amount'] = 0;
            $purchase['total_amount'] = 0;
            $purchase['amt_to_pay'] = 0;
            $purchase['notation'] = $inputs['notation'];
            $purchase['quotation_id'] = $quotation_id = $inputs['quotation_id'];


            $purchaseId = Purchase::create($purchase);

            $purchaseDetails = [];
            $purchaseDetails['purchase_id'] = $purchaseId->id;

            $ledgerDescription = "".$inputs['notation'];

            $total_amount = 0;

            foreach ($value as $i) {


                if (!isset($product[$i]) || !isset($qty[$i]))
                    break;


                if ($qty[$i] < 1) {
                    break;
                }


                $item = Item::find($product[$i]);
                $purchaseDetails['item_id'] = $item_id = $product[$i];
                $purchaseDetails['unit'] = $item->unit;
                $purchaseDetails['qty'] = $quantity = $qty[$i];
//            $purchaseDetails['price'] = $price[$i];
//            $purchaseDetails['total_price'] = $qty[$i]*$price[$i];
                $purchaseDetails['total_price'] = $total_price[$i];
                $purchaseDetails['price'] = $price = create_float_format($total_price[$i] / $qty[$i], 2);
                $purchaseDetails['description'] = $description[$i];
                $purchaseDetails['company_id'] = Auth::user()->company_id;
                $purchaseDetails['date'] = $purchaseDate;

                $ledgerDescription = empty($ledgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . ")" : $ledgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")";


                if ($item->warranty > 0) {
                    $purchaseDetails['warranty'] = $item->warranty;
                    $purchaseDetails['warranty_start_date'] = $today = Carbon::today();
                    $purchaseDetails['warranty_end_date'] = $today->addMonths($item->warranty);
                }


//            $total_price = $qty[$i]*$price[$i];
                $total_amount += $total_price[$i];

                PurchaseDetail::create($purchaseDetails);
                $data = [];
                $data['item_id'] = $item_id;
                $data['qty'] = $quantity;
                $data['supplier_id'] = $supplier;

                SupplierPurchases::create($data);

                if($quotation_id)
                {
                    $quotation_detail_id =$quotation_details_id[$i];
                    $quotationDetail = QuotationDetail::find($quotation_detail_id);
                    $quotationDetail->purchase_qty = $qty[$i]+$quotationDetail->purchase_qty ;
                    $quotationDetail->save();
                }

                $this->stock_report($product[$i], $qty[$i], 'purchase', $purchaseDate);
                $this->stockIn($product[$i], $qty[$i]);
            }


            $inputs['paid_amount'] = $total_amount;
            $purchase = [];
            $purchase['total_price'] = $total_amount;
            $purchase['total_amount'] = $total_amount;
            $discount = 0;


            $purchase['paid_amount'] = $inputs['paid_amount'];
            $purchase['total_discount'] = $discount;
            $purchase['amt_to_pay'] = $purchase['total_amount']-$discount;
            $lastAmount = $purchase['amt_to_pay']-$inputs['paid_amount'];

            $purchase['advance_amount'] = $purchase['due_amount'] = 0;
            if($lastAmount<0)
                $purchase['advance_amount'] = (-1)*$lastAmount;
            else
                $purchase['due_amount'] = $lastAmount;


            $purchaseId->update($purchase);


            if($quotation_id)
            {
                $quotation = Quotation::find($quotation_id);
                $quotation->purchase_id = $quotation->purchase_id != "" ? $quotation->purchase_id.",".$purchaseId->id : $purchaseId->id;
                $quotation->save();

                $inputs['notation'] = "Ref# ".$quotation->ref;
            }


            $inputs['transaction_code'] = transaction_code_generate();

            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->supplier_id = $supplier;
            $save_transaction->purchase_id = $purchaseId->id;
            $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
            $save_transaction->date = $purchaseDate;
            $save_transaction->amount = $purchase['amt_to_pay'];
            $save_transaction->notation = $inputs['notation'];
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Purchases";
            $save_transaction->save();

            $purchase = new PurchaseTransaction($purchaseId->id);
            $purchase->updateTransactions();


            $status = ['type' => 'success', 'message' => 'Purchase Done Successfully'];

        }



        if($status['type'] == 'success')
        {
            return redirect()->route('member.quotations.index')->with('status', $status);
        }else{

            return redirect()->back()->with('status', $status);
        }


    }



}
