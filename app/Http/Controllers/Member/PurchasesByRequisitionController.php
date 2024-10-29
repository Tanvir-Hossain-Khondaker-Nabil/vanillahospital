<?php

namespace App\Http\Controllers\Member;

use App\DataTables\PurchasesDataTable;
use App\Http\Services\PurchaseTransaction;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\SupplierOrCustomer;
use App\Models\SupplierPurchases;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;

class PurchasesByRequisitionController extends Controller
{
    use FileUploadTrait, TransactionTrait, StockTrait, CompanyInfoTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
        $purchase['requisition_id'] = isset($inputs['requisition_id']) ? $inputs['requisition_id'] : 0;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = $this->fileUpload($file, '/file/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $purchase['file'] = $upload;

        }

        DB::beginTransaction();
        try {

            $purchaseId = Purchase::create($purchase);
            Requisition::find($purchase['requisition_id'])->update(['purchase_id' => $purchaseId->id, 'is_purchased' => 1]);

            $purchaseDetails = [];
            $purchaseDetails['purchase_id'] = $purchaseId->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $price = $request->price;
            $total_price = $request->total_price;
            $description = $request->description;
//        $sales_price = $request->sales_price;
//        $last_purchase_qty = $request->last_purchase_qty ?? [];
            $available_stock = $request->available_stock;
            $requisition_details_id = $request->requisition_details_id;
            $area = $request->area_id;

            $ledgerDescription = "" . $inputs['notation'];

            $total_amount = 0;
            for ($i = 0; $i < count($product); $i++) {

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
//            $purchaseDetails['sales_price'] = $sales_price[$i];
//            $purchaseDetails['last_purchase_qty'] = $last_purchase_qty[$i];
                $purchaseDetails['available_stock'] = $available_stock[$i];
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

                $details = PurchaseDetail::create($purchaseDetails);

                if (isset($requisition_details_id[$i]))
                    RequisitionDetail::find($requisition_details_id[$i])->update(['purchase_details_id' => $details->id, 'purchase_status' => 1]);

                $data = [];
                $data['item_id'] = $item_id;
                $data['qty'] = $quantity;
                $data['supplier_id'] = $inputs['supplier_id'];
                SupplierPurchases::create($data);

                $this->stock_report($product[$i], $qty[$i], 'purchase', $purchaseDate);
                $this->stockIn($product[$i], $qty[$i]);

            }


            $purchase = [];
            $purchase['total_price'] = $total_amount;
            $purchase['total_amount'] = $total_amount + $inputs['transport_cost'] + $inputs['unload_cost'] + $inputs['bank_charge'];

            if ($inputs['discount_type'] == "Fixed") {
                $discount = $inputs['discount'];
            } else {
                $discount = $total_amount * $inputs['discount'] / 100;
            }

            $purchase['paid_amount'] = $inputs['paid_amount'];
            $purchase['total_discount'] = $discount;
            $purchase['amt_to_pay'] = $purchase['total_amount'] - $discount;
            $lastAmount = $purchase['amt_to_pay'] - $inputs['paid_amount'];

            $purchase['advance_amount'] = $purchase['due_amount'] = 0;
            if ($lastAmount < 0)
                $purchase['advance_amount'] = -1 * $lastAmount;
            else
                $purchase['due_amount'] = $lastAmount;

            $purchaseId->update($purchase);


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


        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.purchase.show', $purchaseId->id)->with('status', $status);
        } else {

            return redirect()->back()->with('status', $status);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['purchase'] = $purchase = Purchase::findorFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $data['purchase_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($purchase->memo_no, "C128A", 2, 50) . '" alt="' . $purchase->memo_no . '"   />';
        $data = $this->company($data);

        return view('member.purchase.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function print_purchases($id)
    {
        $data['purchase'] = $purchase = Purchase::findorFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $data['purchase_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($purchase->memo_no, "C128A", 2, 50) . '" alt="' . $purchase->memo_no . '"   />';
        $data = $this->company($data);
        $data['report_title'] = "Purchase Invoice";

        return view('member.purchase.print_purchases', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $purchase = Purchase::find($id);
        $data['memo_no'] = $purchase->memo_no;
        $data['chalan_no'] = $purchase->chalan;
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->authCompany()->latest()->pluck('name', 'id');
        $data['products'] = Item::authCompany()->latest()->pluck('item_name', 'id');
        $data['purchase_products'] = Item::whereNotIn('id', $purchase->purchase_details()->pluck('item_id')->toArray())->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');
        $data['areas'] = Area::active()->get()->pluck('name', 'id');

        return view('member.purchase.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchaseId = Purchase::find($id);

        $inputs = $request->all();

        $purchase = [];
//        $purchase['memo_no'] = $inputs['memo_no'];
//        $purchase['chalan'] = $inputs['chalan'];
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
        $purchase['vehicle_number'] = $inputs['vehicle_number'];


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = $this->fileUpload($file, '/file/', null);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $purchase['file'] = $upload;

        }

//        DB::beginTransaction();
//        try{

        $purchaseId->update($purchase);
        $purchaseDetails = [];
        $purchaseDetails['purchase_id'] = $id;

        $product = $request->product_id;
        $unit = $request->unit;
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $request->total_price;
        $description = $request->description;
//        $sales_price = $request->sales_price;
        $available_stock = $request->available_stock;
        $last_purchase_qty = $request->last_purchase_qty;
        $area = $request->area_id;

        $ledgerDescription = "" . $inputs['notation'];
        $purchase_details_id = $request->purchaseDetails;


        $deletePurchaseDetail = PurchaseDetail::where('purchase_id', $id)->whereNotIn('id', $purchase_details_id)->get();


//        dd($request->purchaseDetails);
        foreach ($deletePurchaseDetail as $value) {
            $purchase_detail_id = PurchaseDetail::find($value->id);
            $purchase_detail_id->purchase_starus = "edit";
            $purchase_detail_id->update();

            $this->stock_report($value->item_id, $purchase_detail_id->qty, 'delete purchase', $purchaseDate);
            $this->stockOut($value->item_id, $purchase_detail_id->qty, 'delete purchase');
            $value->delete();
        }

        $purchaseDetails = [];
        $total_amount = 0;
        for ($i = 0; $i < count($product); $i++) {
            $item = Item::find($product[$i]);

            if (!isset($product[$i]) || !isset($qty[$i]))
                break;


            if ($qty[$i] < 1) {
                break;
            }

            if ($purchase_details_id[$i] != "new") {
                $purchase_detail_id = PurchaseDetail::find($purchase_details_id[$i]);
            } else {
                $purchaseDetails['purchase_id'] = $purchaseId->id;
            }

            $purchaseDetails['item_id'] = $item_id = $product[$i];
            $purchaseDetails['unit'] = $item->unit;
            $purchaseDetails['qty'] = $quantity = $qty[$i];
            $purchaseDetails['price'] = $p_price = $price[$i];
            $purchaseDetails['total_price'] = $qty[$i] * $price[$i];
//            $purchaseDetails['area_id'] = $area[$i];
            $purchaseDetails['description'] = $description[$i];
//            $purchaseDetails['sales_price'] = $sales_price[$i];
            $purchaseDetails['available_stock'] = $available_stock[$i];
            $purchaseDetails['last_purchase_qty'] = $last_purchase_qty[$i];
            $purchaseDetails['company_id'] = Auth::user()->company_id;
            $purchaseDetails['date'] = $purchaseDate;

            $ledgerDescription = empty($ledgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $p_price . ")" : $ledgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $p_price . ")";


            if ($item->warranty > 0) {
                $purchaseDetails['warranty'] = $item->warranty;
                $purchaseDetails['warranty_start_date'] = $today = Carbon::today();
                $purchaseDetails['warranty_end_date'] = $today->addMonths($item->warranty);
            }


            $total_price = $qty[$i] * $price[$i];
            $total_amount += $total_price;

            $data = [];
            $data['item_id'] = $item_id;
            $data['qty'] = $quantity;
            $data['supplier_id'] = $inputs['supplier_id'];
            SupplierPurchases::create($data);

            if ($purchase_details_id[$i] != "new") {

                if ($product[$i] != $purchase_detail_id->item_id) {
                    $this->stock_report($purchase_detail_id->item_id, $purchase_detail_id->qty, 'delete purchase', $purchaseDate);
                    $this->stockOut($purchase_detail_id->item_id, $purchase_detail_id->qty, 'delete purchase');
                } else {
                    $this->stock_report($product[$i], $purchase_detail_id->qty, 'delete purchase', $purchaseDate);
                    $this->stockOut($product[$i], $purchase_detail_id->qty, 'delete purchase');
                }

                $purchase_detail_id->update($purchaseDetails);

            } else {
                PurchaseDetail::create($purchaseDetails);
            }

            $this->stock_report($product[$i], $qty[$i], 'purchase', $purchaseDate);
            $this->stockIn($product[$i], $qty[$i], 'Purchase Edit');
        }


        $purchase = [];
        $purchase['total_price'] = $total_amount;
        $purchase['total_amount'] = $total_amount + $inputs['transport_cost'] + $inputs['unload_cost'] + $inputs['bank_charge'];

        if ($inputs['discount_type'] == "Fixed") {
            $discount = $inputs['discount'];
        } else {
            $discount = $total_amount * $inputs['discount'] / 100;
        }

        $purchase['paid_amount'] = $inputs['paid_amount'];
        $purchase['total_discount'] = $discount;
        $purchase['amt_to_pay'] = $purchase['total_amount'] - $discount;

        $lastAmount = $purchase['amt_to_pay'] - $inputs['paid_amount'];

        $purchase['advance_amount'] = $purchase['due_amount'] = 0;
        if ($lastAmount < 0)
            $purchase['advance_amount'] = -1 * $lastAmount;
        else
            $purchase['due_amount'] = $lastAmount;

        $purchaseId->update($purchase);

        $save_transaction = Transactions::where('purchase_id', $purchaseId->id)->first();
        $save_transaction->amount = $purchase['amt_to_pay'];
        $save_transaction->notation = $inputs['notation'];
        $save_transaction->supplier_id = $inputs['supplier_id'];
        $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
        $save_transaction->date = $purchaseDate;
        $save_transaction->save();
        $inputs['transaction_method'] = "Purchases";

        // Edit is not complete yet here to bottom

        $account = CashOrBankAccount::find($request->cash_or_bank_id);

        if (isset($request->supplier_id)) {
            $sharer = SupplierOrCustomer::find($request->supplier_id);
        } else {
            $inputs['sharer_name'] = '';
        }

        $trans_details = TransactionDetail::where('transaction_id', $save_transaction->id)->get();
        foreach ($trans_details as $value) {
            $acc_balance = [];
            $acc_balance['account_type_id'] = $value->account_type_id;
            $acc_balance['account_head_name'] = $value->account_type->display_name;
            $acc_balance['transaction_id'] = $save_transaction->id;
            $acc_balance['transaction_type'] = "dr";
            $acc_balance['date'] = $purchaseDate;
            $acc_balance['amount'] = $value->amount;
            $amount = $this->createAccountHeadBalanceHistory($acc_balance);

            $acc_balance['amount'] = $amount;
            $this->createAccountHeadDayWiseBalanceHistory($acc_balance);

            $check = [];
            $inputs['sharer_name'] = $check['sharer_name'] = $sharer->name;
            $check['account_type_id'] = $sharer->account_type_id;
            $check['amount'] = $amount;
            $this->updateSharerBalance($check);

            $value->delete();
        }

        $dr_against_name = '';
        $against_account_type = AccountType::where('name', 'purchase')->first();

        $inputs['account_type_id'] = $account->account_type_id;
        $inputs['against_account_type_id'] = $against_account_type->account_type_id;
        $inputs['against_account_name'] = $against_account_type->display_name;
        $inputs['account_name'] = $account->account_type->display_name;
        $inputs['to_account_name'] = '';
        $inputs['transaction_id'] = $save_transaction->id;
        $inputs['amount'] = $inputs['paid_amount'];
        $inputs['transaction_type'] = 'cr';
        $inputs['description'] = " Purchase Id : " . $purchaseId->id . " Purchase product " . $ledgerDescription;
        $this->createCreditAmount($inputs);

        if ($inputs['paid_amount'] > 0) {
            $dr_against_name = $account->account_type->display_name;
        }

        if ($purchase['due_amount'] > 0) {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] = $sharer->account_type->display_name;
            $dr_against_name = (!empty($dr_against_name) ? $dr_against_name . " & " : null) . $sharer->account_type->display_name;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $purchase['total_amount'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = " Purchase Id : " . $purchaseId->id . " Purchase product " . $ledgerDescription;
            $this->createDebitAmount($inputs);
        }

        if ($purchase['advance_amount'] > 0) {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] = $sharer->account_type->display_name;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $purchase['advance_amount'];
            $inputs['transaction_type'] = 'dr';
            $inputs['description'] = "Advance payment and Purchase Id : " . $purchaseId->id . " Purchase product " . $ledgerDescription;
            $this->createDebitAmount($inputs);
        }

        $inputs['account_type_id'] = $against_account_type->id;
        $inputs['account_name'] = $against_account_type->display_name;
        $inputs['against_account_type_id'] = null;
        $inputs['against_account_name'] = $dr_against_name;
        $inputs['amount'] = $purchase['amt_to_pay'];
        $inputs['transaction_type'] = 'dr';
        $this->createDebitAmount($inputs);


        /*
         * TODO: Create Transaction History for Purchases
         */


        $status = ['type' => 'success', 'message' => 'Purchase update Successfully'];


//        }catch (\Exception $e){
//
//                $status = ['type' => 'danger', 'message' => 'Unable to update'];
//                DB::rollBack();
//        }
//
//        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.purchase.show', $purchaseId->id)->with('status', $status);
        } else {
            return redirect()->back()->with('status', $status);
        }
    }

    public function entry_item_serial($purchase_id, $item_id)
    {
        $item_details = ItemDetail::where('purchase_id', $purchase_id)->where('item_id', $item_id)->count();

        $purchase_item = PurchaseDetail::where('purchase_id', $purchase_id)->where('item_id', $item_id)->first();

        if (!$purchase_item) {
            $status = ['type' => 'danger', 'message' => 'No Purchase Item found'];

            return redirect()->back()->with('status', $status);
        }

        if ($item_details > 0) {
            $status = ['type' => 'danger', 'message' => 'Purchase Item Serial already Added'];

            return redirect()->back()->with('status', $status);
        }

        $data = [];
        $data['item_count'] = $purchase_item->qty;
        $data['item_name'] = $purchase_item->item;
        $data['item_id'] = $item_id;
        $data['purchase_id'] = $purchase_id;

        return view('member.purchase.entry_item_serial', $data);
    }

    public function item_serial_store(Request $request)
    {
//        $inputs = $request->all();
        $purchase_id = $request->purchase_id;
        $item_id = $request->item_id;

        $item_details = ItemDetail::where('purchase_id', $purchase_id)->where('item_id', $item_id)->count();

        $purchase_item = PurchaseDetail::where('purchase_id', $purchase_id)->where('item_id', $item_id)->first();

        if (!$purchase_item) {
            $status = ['type' => 'danger', 'message' => 'No Purchase Item found'];

            return redirect()->back()->with('status', $status);
        }

        if ($item_details > 0) {
            $status = ['type' => 'danger', 'message' => 'Purchase Item Serial already Added'];

            return redirect()->back()->with('status', $status);
        }

        $serial = $request->serial;

        for ($i = 0; $i < $purchase_item->qty; $i++) {
            $data = [];
            $data['purchase_id'] = $purchase_id;
            $data['item_id'] = $item_id;
            $data['user_id'] = Auth::user()->id;
            $data['company_id'] = Auth::user()->company_id;
            $data['sale_status'] = 0;
            $data['serial_number'] = $serial[$i];
            ItemDetail::create($data);
        }

        $status = ['type' => 'success', 'message' => 'Purchase Item Serial Added'];

        return redirect()->route('member.purchase.show', $purchase_id)->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        if (!$purchase) {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }


        foreach ($purchase->purchase_details as $value) {
            $this->stock_report($value->item_id, $value->qty, 'delete purchase', $purchase->date);
            $this->stockOut($value->item_id, $value->qty, 'delete purchase');
        }

        $modal = Transactions::authCompany()->where('purchase_id', $id)->first();

        if ($modal) {

            $this->transactionRevertAmount($modal->id);
            $data = [];
            foreach ($modal->transaction_details as $key => $value) {
                $data[$key]['account_type_id'] = $value->account_type_id;
                $data[$key]['date'] = $value->date;
                $data[$key]['company_id'] = $value->company_id;
            }

            $modal->transaction_details()->delete();
            $modal->delete();

            foreach ($data as $value) {
                $inputs = [];
                $inputs['account_type_id'] = $value['account_type_id'];
                $inputs['date'] = $value['date'];


                $transactionCheck = TransactionDetail::where('date', $value['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->get();

                if (count($transactionCheck) < 1) {
                    AccountHeadDayWiseBalance::where('date', $inputs['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->delete();
                }

                $this->updateAccountHeadBalanceByDate($inputs);
                $this->updateSharerBalance($inputs);
                $this->updateCashBankBalance($inputs);
            }
        }

        $purchase->purchase_details()->delete();
        $purchase->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }


}
