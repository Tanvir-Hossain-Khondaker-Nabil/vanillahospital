<?php

namespace App\Http\Controllers\Member;


use App\DataTables\SalesDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TrackShoppingBags;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Barryvdh\DomPDF\Facade as PDF;

class WarehouseSaleController extends Controller
{
    use TransactionTrait, StockTrait, CompanyInfoTrait;


    protected $print_system, $company_id;


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query) {
            $query->where('name', '!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query) {
            $query->where('name', 'shopping_bags');
        })->authCompany()->latest()->get();

        return view('member.sales.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $saleDate = db_date_format($inputs['date']);

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

        DB::beginTransaction();
        try {

            $saleInsert = Sale::create($sale);

            $saleDetails = [];
            $saleDetails['sale_id'] = $saleInsert->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $price = $request->price;
            $product_info = $request->product_info;
//        $description = $request->description;
//        $last_sale_qty = $request->last_sale_qty;
            $available_stock = $request->available_stock;
            $per_discount = $request->per_discount;


            $total_amount = $total_discount = $insert = 0;
            $count_product = 0;
            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = "" . $inputs['notation'];
            for ($i = 0; $i < count($product); $i++) {

                if (!isset($product[$i]) || !isset($qty[$i]))
                    break;
                else
                    $count_product++;


                if ($qty[$i] < 0 || $price[$i] < 0) {
                    break;
                }


                $item = Item::find($product[$i]);
                $saleDetails['item_id'] = $item_id = $product[$i];
                $saleDetails['unit'] = $item->unit;
                $saleDetails['qty'] = $quantity = $qty[$i];
                $saleDetails['price'] = $price[$i];
                $saleDetails['total_price'] = create_float_format(($qty[$i] * $price[$i]));
                $saleDetails['product_info'] = $serial = $product_info[$i];
//            $saleDetails['description'] = $description[$i];
                $saleDetails['discount'] = $per_discount[$i];
//            $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
                $saleDetails['available_stock'] = $available_stock[$i];
                $saleDetails['company_id'] = Auth::user()->company_id;
                $saleDetails['date'] = $saleDate;

                $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $price[$i] . ")" : $saleLedgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price[$i] . ")";


                if ($item->warranty > 0) {
                    $saleDetails['warranty'] = $item->warranty;
                    $saleDetails['warranty_start_date'] = Carbon::today();
                    $saleDetails['warranty_end_date'] = Carbon::today()->addMonths($item->warranty);
                }


                $total_price = create_float_format(($qty[$i] * $price[$i]));
                $total_amount += $total_price;
                $total_discount += $per_discount[$i];

                $stockCheck = Stock::where('item_id', $item_id)->first();
                if ($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                    break;


                $item = ItemDetail::where('serial_number', $serial)->where('item_id', $item_id)->authCompany()->first();

                if ($item)
                    ItemDetail::where('serial_number', $serial)->where('item_id', $item_id)->update(['sale_status' => 1]);


                $purchaseService = new PurchaseService();
                $purchasePrice = $purchaseService->averagePurchasePrice($item_id);
                $saleDetails['purchase_price'] = $purchasePrice;

                $sale = SaleDetails::create($saleDetails);

                if ($sale) {
                    array_push($sale_product, $item_id);
                    array_push($sale_product_qty, $quantity);
                    $insert++;
                }

                $this->stock_report($product[$i], $qty[$i], 'sale', $saleDate);
                $this->stockOut($product[$i], $qty[$i]);
                //  $this->createStockHistory($product[$i], $qty[$i], 'Stock Out', $p_stock, $c_stock);

            }


            if ($insert == $count_product) {

                $sale = [];
//            $sale['total_price'] = $total_amount;
                $sale['total_price'] = $total_amount;

//            if($inputs['discount_type']=="fixed")
//            {
                $discount = $inputs['discount'];
//            }else{
//                $discount = ($total_amount*$inputs['discount']/100);
//                $discount = $discount>0 ? $discount+$total_discount : $total_discount;
//            }

                $sale['paid_amount'] = $inputs['paid_amount'];
                $sale['total_discount'] = $discount;
                $sale['amount_to_pay'] = $amount_to_pay = $total_amount - $discount + $inputs['transport_cost'] + $inputs['unload_cost'];
                $sale['grand_total'] = $total_amount + $inputs['transport_cost'] + $inputs['unload_cost'];
                $sale['due'] = $sale['amount_to_pay'] - $inputs['paid_amount'];

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

                if (isset($request->customer_id)) {
                    $sharer = SupplierOrCustomer::find($request->customer_id);
                    $inputs['sharer_name'] = $sharer ? $sharer->name : '';
                } else {
                    $sharer = null;
                    $inputs['sharer_name'] = '';
                }

                $this->create_sale_transaction($account, $save_transaction, $saleInsert, $discount, $sale['due'], $sharer, $total_amount, $inputs, $saleLedgerDescription);


                /*
                 * TODO: Create Transaction History for Sales
                 */

                $status = ['type' => 'success', 'message' => 'Sales done Successfully'];
            } else {

                for ($i = 0; $i < count($sale_product); $i++) {

                    $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                    $this->stockIn($sale_product[$i], $sale_product_qty[$i], 'sale Return');
                }

                $status = ['type' => 'danger', 'message' => 'Sales product out of stock'];
                $saleInsert->delete();
            }


        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.sales.show', $saleInsert->id)->with('status', $status);
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
    public function show(Request $request, $id)
    {
        $data['sales'] = $sale = Sale::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $sale->sale_code;
        $data['sale_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        $previousDue = 0;

        if ($sale->customer_id > 0) {
            $due = $sale->customer->previous_balance()->latest()->first();
            $previousDue = $due ? $due->previous_balance : 0;
        }

        $data['previousDue'] = $previousDue;

        if (isset($request->based) && $request->based == 'profit_invoice') {
            return view('member.sales.profit_show', $data);
        } else {
            return view('member.sales.show', $data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function print_sale(Request $request, $id)
    {
        $data['sales'] = $sale = Sale::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $sale->sale_code;
        $data['sale_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        $previousDue = 0;
        if ($sale->customer_id > 0) {
            $due = $sale->customer->previous_balance()->latest()->first();
            $previousDue = $due ? $due->previous_balance : 0;
        }

        $data['previousDue'] = $previousDue;
        $data['based'] = $request->based;
        $data['pad_type'] = $request->pad_type;
        $data['hasImages'] = 0;

        if (isset($request->based) && $request->based == 'profit_invoice') {
            return view('member.sales._print_profit_sales', $data);
        } else if (isset($request->based) && $request->based == 'download') {
            $barcode = $sale->quotation ? $sale->quotation->ref : $barcode;
            $file_name = $data['report_title'] = "Sale_Invoice_" . $barcode;

            if (config('settings.quotation') && $sale->quotation) {
                $pdf = PDF::loadView('member.sales.download_invoice', $data);
            } else {
                $pdf = PDF::loadView('member.sales.' . $this->print_system . '_download_sales', $data);
            }

            return $pdf->download($file_name . ".pdf");
        } else {
            $this->check_print_system();

            if ($sale->track_sale == "pos")
                return view('member.sales.' . 'pos_print_sales', $data);
            else
                return view('member.sales.' . '_print_sales', $data);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function print_calan(Request $request, $id)
    {
        $data['sales'] = $sale = Sale::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__ . "/cache/");
        $barcode = $sale->sale_code;
        $data['sale_barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        $data['based'] = $request->based;
        $data['hasImages'] = 0;

        $data['pad_type'] = $request->pad_type;
        $barcode = $sale->quotation ? $sale->quotation->ref : $barcode;
        $file_name = $data['report_title'] = "Sale_Chalan_" . $barcode;

        if (isset($request->based) && $request->based == 'download') {
            if (config('settings.quotation') && $sale->quotation) {
                $pdf = PDF::loadView('member.sales.download_chalan', $data);
            } else {
                $pdf = PDF::loadView('member.sales._print_calan_sales', $data);
            }

            return $pdf->download($file_name . ".pdf");
        } else {
            $this->check_print_system();

            return view('member.sales._print_calan_sales', $data);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = Sale::findOrFail($id);

        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query) {
            $query->where('name', '!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');

        $data['areas'] = Area::active()->get()->pluck('name', 'id');
        $data['bags'] = Item::whereHas('category', function ($query) {
            $query->where('name', 'shopping_bags');
        })->authCompany()->select('*', 'items.id as item_id')->latest()->get();


        return view('member.sales.edit', $data);
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

        $saleFind = Sale::findOrFail($id);

        $inputs = $request->all();

        $sale = [];
        $sale['memo_no'] = $inputs['memo_no'];
        $sale['chalan_no'] = $inputs['chalan_no'];
        $sale['customer_id'] = $inputs['customer_id'];
        $sale['cash_or_bank_id'] = $inputs['cash_or_bank_id'];
        $sale['payment_method_id'] = $inputs['payment_method_id'];
        $sale['delivery_type_id'] = $inputs['delivery_type_id'];
        $sale['date'] = $saleDate = db_date_format($inputs['date']);
        $sale['total_price'] = $inputs['sub_total'];
        $sale['discount_type'] = isset($inputs['discount_type']) ? $inputs['discount_type'] : "fixed";
        $sale['discount'] = $inputs['discount'] ?? 0;
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
        try {

            $saleFind->update($sale);

            $saleDetails = [];
            $saleDetails['sale_id'] = $saleFind->id;

            $product = $request->product_id;
            $unit = $request->unit;
            $qty = $request->qty;
            $total_price = $request->total_price;
            $product_info = $request->product_info;
//        $description = $request->description;
            $available_stock = $request->available_stock;
//        $last_sale_qty = $request->last_sale_qty;
            $sale_details_id = $request->sale_details_id;
            $per_discount = $request->per_discount;


            $deleteSaleDetails = SaleDetails::where('sale_id', $id)->whereNotIn('id', $sale_details_id)->get();

            foreach ($deleteSaleDetails as $value) {
                $sale_detail_id = SaleDetails::find($value->id);
                $sale_detail_id->sale_status = "edit";
                $sale_detail_id->update();

                $this->stockIn($value->item_id, $sale_detail_id->qty, 'delete sale');
                $value->delete();
                $this->stock_report($value->item_id, $sale_detail_id->qty, 'delete sale', $saleDate);
            }


            $total_amount = $total_discount = $insert = 0;
            $count_product = 0;
            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = "" . $inputs['notation'];
            for ($i = 0; $i < count($product); $i++) {

                if (!isset($product[$i]) || !isset($qty[$i]))
                    break;
                else
                    $count_product++;


                if ($qty[$i] <= 0 || $total_price[$i] <= 0) {
                    break;
                }


                if ($sale_details_id[$i] != "new") {

                    $sale_detail_id = SaleDetails::find($sale_details_id[$i]);
                    $saleDetails['qty'] = 0;
                    $sale_detail_id->update($saleDetails);

                    $this->stock_report($product[$i], 0, 'sale', $saleDate);
                } else {

                    $saleDetails['sale_id'] = $saleFind->id;
                }

                $item = Item::find($product[$i]);
                $saleDetails['item_id'] = $item_id = $product[$i];
                $saleDetails['unit'] = $item->unit;
                $saleDetails['qty'] = $quantity = $qty[$i];
                $saleDetails['total_price'] = $total_price[$i];
                $saleDetails['product_info'] = $serial = $product_info[$i];
//            $saleDetails['description'] = $description[$i];
                $saleDetails['discount'] = $per_discount[$i];
//            $saleDetails['last_sale_qty'] = $last_sale_qty[$i];
                $saleDetails['available_stock'] = $available_stock[$i];
                $saleDetails['company_id'] = Auth::user()->company_id;
                $saleDetails['date'] = $saleDate;

                $total_discount += $per_discount[$i];


                $purchaseService = new PurchaseService();
                $purchasePrice = $purchaseService->averagePurchasePrice($item_id);
                $saleDetails['purchase_price'] = $purchasePrice;

                if ($item->warranty > 0) {
                    $saleDetails['warranty'] = $item->warranty;
                    $saleDetails['warranty_start_date'] = Carbon::today();
                    $saleDetails['warranty_end_date'] = Carbon::today()->addMonths($item->warranty);
                }


                $price = $total_price[$i] / $qty[$i];
                $saleDetails['price'] = create_float_format($price, 3);
                $total_amount += $total_price[$i];


                $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")" : $saleLedgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")";


                $stockCheck = Stock::where('item_id', $item_id)->first();


                if ($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                    break;

                $item = ItemDetail::where('serial_number', $serial)->where('item_id', $item_id)->authCompany()->first();

                if ($item)
                    ItemDetail::where('serial_number', $serial)->where('item_id', $item_id)->update(['sale_status' => 1]);


                if ($sale_details_id[$i] != "new") {

                    $sale = $sale_detail_id->update($saleDetails);
                    $insert++;
                } else {
                    $sale = SaleDetails::create($saleDetails);

                    if ($sale) {
                        array_push($sale_product, $item_id);
                        array_push($sale_product_qty, $quantity);
                        $insert++;
                    }
                }

                $this->stock_report($product[$i], $qty[$i], 'sale', $saleDate);
                $this->stockOut($product[$i], $qty[$i], 'Sale Edit');

            }

            if ($insert == $count_product) {
                $bags = Item::whereHas('category', function ($query) {
                    $query->where('name', 'shopping_bags');
                })->latest()->get();

                $trackBags = TrackShoppingBags::where('sale_id', $id)->get();
//            dd($trackBags);
                foreach ($trackBags as $bag) {
                    $this->stock_report($bag->bag_id, $bag->qty, 'sale return', $saleDate);
                    $this->stockIn($bag->bag_id, $bag->qty, 'sale return');
                    $bag->delete();
                }

                foreach ($bags as $value) {
                    if (!empty($inputs['shopping_bags_' . $value->id])) {
                        $saleBag = [];
                        $saleBag['sale_id'] = $saleFind->id;
                        $saleBag['bag_id'] = $value->id;
                        $saleBag['qty'] = $qt = $inputs['shopping_bags_' . $value->id];
                        TrackShoppingBags::create($saleBag);

                        $this->stock_report($value->id, $qt, 'sale', $saleDate);
                        $this->stockOut($value->id, $qt, 'Sale Edit');

                    }

                }

                $sale = [];
//            $sale['total_price'] = $total_amount;
                $sale['total_price'] = $total_amount;

//            if($inputs['discount_type']=="fixed")
//            {
                $discount = $inputs['discount'];
//            }else{
//                $discount = ($total_amount*$inputs['discount']/100);
//                $discount = $discount>0 ? $discount+$total_discount : $total_discount;
//            }

                $sale['paid_amount'] = $inputs['paid_amount'];
                $sale['total_discount'] = $discount;
                $sale['amount_to_pay'] = $total_amount - $discount + $inputs['unload_cost'] + $inputs['transport_cost'];
                $sale['grand_total'] = $total_amount + $inputs['unload_cost'] + $inputs['transport_cost'];
                $sale['due'] = $sale['amount_to_pay'] - $inputs['paid_amount'];

                $saleFind->update($sale);

                $save_transaction = Transactions::where('sale_id', $saleFind->id)->first();
                $save_transaction->supplier_id = $inputs['customer_id'];
                $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
                $save_transaction->date = $saleDate;
                $save_transaction->amount = $total_amount;
                $save_transaction->notation = $inputs['notation'];
                $save_transaction->save();
                $inputs['transaction_method'] = "Sales";

                $account = CashOrBankAccount::find($request->cash_or_bank_id);

                if (isset($request->customer_id)) {
                    $sharer = SupplierOrCustomer::find($request->customer_id);
                    $inputs['sharer_name'] = $sharer ? $sharer->name : '';
                } else {
                    $sharer = null;
                    $inputs['sharer_name'] = '';
                }

                $trans_details = TransactionDetail::where('transaction_id', $save_transaction->id)->get();
                foreach ($trans_details as $value) {
                    $acc_balance = [];
                    $acc_balance['account_type_id'] = $value->account_type_id;
                    $acc_balance['account_head_name'] = $value->account_type->display_name;
                    $acc_balance['transaction_id'] = $save_transaction->id;
                    $acc_balance['transaction_type'] = "dr";
                    $acc_balance['date'] = $saleDate;
                    $acc_balance['amount'] = $value->amount;
                    $amount = $this->createAccountHeadBalanceHistory($acc_balance);

                    $acc_balance['amount'] = $amount;
                    $this->createAccountHeadDayWiseBalanceHistory($acc_balance);

                    if ($sharer) {
                        $check = [];
                        $check['sharer_name'] = $inputs['sharer_name'];
                        $check['account_type_id'] = $sharer->account_type_id;
                        $check['amount'] = $amount;
                        $this->updateSharerBalance($check);
                    }


                    $value->delete();
                }

                $this->create_sale_transaction($account, $save_transaction, $saleFind, $discount, $sale['due'], $sharer, $total_amount, $inputs, $saleLedgerDescription);


                $status = ['type' => 'success', 'message' => 'Sales update Successfully'];
            } else {

                for ($i = 0; $i < count($sale_product); $i++) {

                    $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                    $this->stockIn($sale_product[$i], $sale_product_qty[$i], 'sale Return');
                    //  $this->createStockHistory($sale_product[$i], $sale_product_qty[$i], 'Stock In', $p_stock, $c_stock);
                }
                $status = ['type' => 'danger', 'message' => 'Sales product out of stock'];
//            $saleFind->delete();
            }


        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to save'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.sales.show', $saleFind->id)->with('status', $status);
        } else {

            return redirect()->back()->with('status', $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        if (!$sale) {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }

        foreach ($sale->sale_details as $value) {
            $this->stock_report($value->item_id, $value->qty, 'delete sale', $sale->date);
            $this->stockIn($value->item_id, $value->qty, 'delete sale');
        }

        $modal = Transactions::authCompany()->where('sale_id', $id)->first();

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

        $sale->sale_details()->delete();
        $sale->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }


    protected function create_sale_transaction($account, $save_transaction, $saleInsert, $discount, $due, $sharer, $total_amount, $inputs, $saleLedgerDescription)
    {

        $against_account_type = AccountType::where('display_name', 'sales')->first();
//                $account_type = AccountType::find($account->account_type_id);
        $inputs['account_type_id'] = $account->account_type_id;
        $inputs['against_account_type_id'] = $against_account_type->account_type_id;
        $inputs['against_account_name'] = $against_account_type->display_name;
        $inputs['account_name'] = $account->account_type->display_name;
        $inputs['to_account_name'] = '';
        $inputs['transaction_id'] = $save_transaction->id;
        $inputs['amount'] = $inputs['paid_amount'];
        $inputs['transaction_type'] = 'dr';
        $inputs['description'] = " Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
        $this->createDebitAmount($inputs);

        $dr_against_name = '';
        if ($inputs['paid_amount'] > 0) {
            $dr_against_name = $account->account_type->display_name;
        }


        if ($discount > 0) {
            $account_type = AccountType::where('name', 'discount')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $discount;
            $inputs['transaction_type'] = 'dr';
            $inputs['description'] = "Discount & " . " Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
            $this->createDebitAmount($inputs);
        }

        if (isset($inputs['shipping_charge']) && $inputs['shipping_charge'] > 0) {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['shipping_charge'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = " Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }

        if ($inputs['unload_cost'] > 0) {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['unload_cost'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = "Labor cost & Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }

        if ($inputs['transport_cost'] > 0) {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['transport_cost'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = "Transport Cost & Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }

        if ($due > 0 && !empty($inputs['sharer_name'])) {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] = $sharer->account_type->display_name;
            $dr_against_name = (!empty($dr_against_name) ? $dr_against_name . " & " : null) . $sharer->account_type->display_name;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $due;
            $inputs['transaction_type'] = 'dr';
            $inputs['description'] = " Sale Id : " . $saleInsert->id . ", Sale product: " . $saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }


        $inputs['account_type_id'] = $against_account_type->id;
        $inputs['account_name'] = $against_account_type->display_name;
        $inputs['against_account_type_id'] = null;
        $inputs['amount'] = $total_amount;
        $inputs['against_account_name'] = $dr_against_name;

        $inputs['transaction_type'] = 'cr';
        $this->createCreditAmount($inputs);
    }

    // Whole Sale End

    private function check_print_system()
    {
        $settings = Setting::where('key', '=', 'print_page_setup')->authCompany()->first();

        if ($settings) {
            $this->print_system = $settings->value;
        } else {
            $this->print_system = '';
        }

        $this->print_system = $this->print_system == "default" ? '' : $this->print_system;
    }
}
