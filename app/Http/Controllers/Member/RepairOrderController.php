<?php

namespace App\Http\Controllers\Member;

use App\DataTables\RepairOrderDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\CashOrBankAccount;
use App\Models\Category;
use App\Models\DefectType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\RepairItem;
use App\Models\RepairOrder;
use App\Models\Sale;
use App\Models\Serviceing;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Milon\Barcode\DNS1D;

class RepairOrderController extends Controller
{
    use TransactionTrait, StockTrait, CompanyInfoTrait, FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RepairOrderDataTable $dataTable)
    {
        return $dataTable->render('member.repair_orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();

        $data['products'] = Item::isRepair()->authCompany()->orderAsc()->latest()->select('item_name', 'id', 'price')->get();

        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['defects'] = DefectType::active()->pluck('name', 'id')->toArray();
        $data['services'] = Serviceing::active()->select('title', 'id', 'price')->get();

        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');


        return view('member.repair_orders.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $services = $request->services;
        $defects = $request->defect_id;

        $inputs = $request->all();
        $today = Carbon::today()->format("Y-m-d");

        $lastRepair = RepairOrder::where('entry_date', $today)->count();
        $lastRepairID = $lastRepair;
        $authId = auth()->id();

        unset($inputs['defect_id']);
        unset($inputs['services']);
        unset($inputs['order_number']);
        unset($inputs['product']);
        unset($inputs['customer_phone']);
        unset($inputs['customer_name']);
        unset($inputs['payment_option']);
        unset($inputs['cash_or_bank_id']);
        unset($inputs['paid_amount']);

        $inputs['order_id'] = $request->order_number;
        $inputs['account_type_id'] = $request->cash_or_bank_id;   // Cash And Bank ID
        $inputs['paid'] = $request->paid_amount;
        $inputs['take_screenshot'] = '';
        $inputs['token'] = code_generate($lastRepairID, $authId);
        $inputs['entry_date'] = $saleDate = db_date_format($inputs['entry_date']);
        $inputs['estimate_delivery_date'] = db_date_format($inputs['estimate_delivery_date']);
        $inputs['defect_type_ids'] = json_encode($defects);
        $inputs['defect_identity_call'] = '';
        $inputs['status'] = 'received';
        $inputs['created_by'] = $authId;
        $inputs['company_id'] = Auth::user()->company_id;


        if($request->hasFile('take_screenshot'))
        {
            $image = $request->file('take_screenshot');

            $fileSupport = ['jpg', 'jpeg','png'];
            $upload = $this->fileUpload($image, '/take_screenshot/', null,$fileSupport);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG, JPEG, PNG'];

                if($request->ajax())
                {
                    return response()->json($status, 404);
                }else{
                    return back()->with('status', $status)->withInput();
                }
            }

            $inputs['take_screenshot'] = $upload;
        }

        DB::beginTransaction();
        try{

            $orderInsert = RepairOrder::create($inputs);
//            dd($orderInsert);

            $total_amount = 0;
            $total_service = 0;
            $total_item_price = $count_product = $insert = 0;

            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = ' (Repair Order) ';


            for($i=0; $i<count($services['id']); $i++){


                if(!isset($services['id'][$i]) || !isset($services['qty'][$i]))
                    break;
                else
                    $count_product++;

                if($services['qty'][$i] < 0)
                {
                    break;
                }


                $repairItems = [];
                $repairItems['repair_id'] = $orderInsert->id;
                $repairItems['item_type'] = $type = $services['type'][$i];
                $repairItems['cover_by_warranty'] = $services['cover'][$i] ?? 0;
                $repairItems['item_id']  = $item_id = $services['id'][$i];
                $repairItems['qty'] = $quantity = $services['qty'][$i];
                $repairItems['price'] = $price = $services['price'][$i];
                $sale_total_price = $quantity*$price;

                if($type == "service")
                {
                    $repairItems['item_type'] = 1;
                    $item = Serviceing::find($item_id);
                    $item_title = $item->title;
                    $total_service += $sale_total_price;
                }else{
                    $repairItems['item_type'] = 0;
                    $item = Item::find($item_id);
                    $item_title = $item->item_name;
                    $total_item_price += $sale_total_price;

                    $stockCheck = Stock::where('item_id', $item_id)->first();
                    if($stockCheck->stock < 0 || $stockCheck->stock < $quantity)
                        break;

                    $this->stock_report($item_id, $quantity, 'sale', $saleDate);
                    $this->stockOut($item_id, $quantity);
                }

                $saleLedgerDescription = empty($saleLedgerDescription) ? $item_title."(".$quantity.")" : $saleLedgerDescription." & ".$item_title."(".$quantity.")";

                $total_amount += $sale_total_price;

                $repairItemList = RepairItem::create($repairItems);

                if($repairItemList)
                {
                    array_push($sale_product, $item_id);
                    array_push($sale_product_qty, $quantity);
                    $insert++;
                }

            }


            if($insert == $count_product)
            {
                $repair = [];

                $discount = $inputs['discount'];
                $repair['total_service_price'] = $total_service;
                $repair['total_item_price'] = $total_item_price;
                $repair['paid'] = $inputs['paid_amount'] = $inputs['paid'];
                $repair['discount'] = $discount;
                $repair['amount_to_pay'] = $amount_to_pay = $total_amount-$discount;
                $repair['due'] = $amount_to_pay-$repair['paid'];

                $orderInsert->update($repair);

                $inputs['payment_method_id'] = 1;
                $inputs['date'] = $inputs['entry_date'];
                $inputs['transaction_code'] = transaction_code_generate();

                $save_transaction = new Transactions();
                $save_transaction->transaction_code = $inputs['transaction_code'];
                $save_transaction->supplier_id = $inputs['customer_id'];
                $save_transaction->repair_order_id = $orderInsert->id;
                $save_transaction->cash_or_bank_id = $inputs['account_type_id'];
                $save_transaction->date = $saleDate;
                $save_transaction->amount = $total_amount;
                $save_transaction->notation = '';
                $save_transaction->transaction_method = $inputs['transaction_method'] = "Repairs";
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
                $this->bankAccountBalanceUpdate($inputs['transaction_method'], $account, $inputs['paid']);


                if(isset($request->customer_id)) {
                    $this->sharerBalanceUpdate($inputs['transaction_method'], $sharer, $inputs['paid']);
                }

                $this->create_sale_transaction($account, $save_transaction, $orderInsert, $discount, $repair['due'], $sharer, $total_amount, $inputs, $saleLedgerDescription);


                $status = ['type' => 'success', 'message' => trans('common.repair_order_save_successfully')];
            }else{

                for($i=0; $i<count($sale_product); $i++) {

                    $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                    $this->stockIn($sale_product[$i], $sale_product_qty[$i],'sale Return');
                }

                $status = ['type' => 'danger', 'message' => trans('common.product_out_of_stock')];
                $orderInsert->delete();
            }


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('sale.unable_to_save')];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.repair_orders.show', $orderInsert->id);
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
    public function show(Request $request, $id)
    {
        $data['orders'] = $sale = RepairOrder::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $sale->token;
        $data['sale_barcode'] = '<img style="width: 150px !important;" src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 30) . '" alt="' . $sale->sale_code . '"   />';
        $data = $this->company($data);

        if(isset($request->based) && $request->based == 'print')
        {
            return view('member.repair_orders._print_invoice', $data);
        }else{
            return view('member.repair_orders.show', $data);
        }
    }

    public function edit($id)
    {
        $data['model']  = $model = RepairOrder::findOrFail($id);
        $sales = Sale::where('sale_code', $model->order_id)->with(['sale_details'])->first();
        $data['pos_html'] = View::make('member.repair_orders.order_details', compact('sales'))->render();

        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();

        $data['products'] = Item::isRepair()->authCompany()->orderAsc()->latest()->select('item_name', 'id', 'price')->get();

        $data['categories'] = Category::all()->pluck('display_name', 'id');
        $data['defects'] = DefectType::active()->pluck('name', 'id')->toArray();
        $data['services'] = Serviceing::active()->select('title', 'id', 'price')->get();

        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->latest()->pluck('title', 'id');


        return view('member.repair_orders.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $orderInsert = RepairOrder::findOrFail($id);

        $this->validate($request, $this->rules($id));

        $services = $request->services;
        $defects = $request->defect_id;

        $inputs = $request->all();
        $today = Carbon::today()->format("Y-m-d");

        $lastRepair = RepairOrder::where('entry_date', $today)->count();
        $lastRepairID = $lastRepair;
        $authId = auth()->id();

        unset($inputs['defect_id']);
        unset($inputs['services']);
        unset($inputs['order_number']);
        unset($inputs['product']);
        unset($inputs['customer_phone']);
        unset($inputs['customer_name']);
        unset($inputs['payment_option']);
        unset($inputs['cash_or_bank_id']);
        unset($inputs['paid_amount']);

        $inputs['order_id'] = $request->order_number;
        $inputs['account_type_id'] = $request->cash_or_bank_id;   // Cash And Bank ID
        $inputs['paid'] = $request->paid_amount;
        $inputs['entry_date'] = $saleDate = db_date_format($inputs['entry_date']);
        $inputs['estimate_delivery_date'] = db_date_format($inputs['estimate_delivery_date']);
        $inputs['defect_type_ids'] = json_encode($defects);
        $inputs['defect_identity_call'] = '';
        $inputs['status'] = 'received';


        DB::beginTransaction();
        try{

//        dd($inputs);
            $orderInsert->update($inputs);
//            dd($orderInsert);

            $total_amount = 0;
            $total_service = 0;
            $total_item_price = $count_product = $insert = 0;

            $sale_product = $sale_product_qty = [];
            $saleLedgerDescription = ' (Repair Order) ';

            $products = $orderInsert->repair_items()->where('item_type', 0)->pluck('item_id')->toArray();

            $deleteRepairDetails = RepairItem::where('repair_id', $id)->delete();

            for($i=0; $i<count($services['id']); $i++){

                $type = $services['type'][$i];

                if(!isset($services['id'][$i]) || !isset($services['qty'][$i]))
                    break;
                else
                    $count_product++;

                if($services['qty'][$i] < 0)
                {
                    break;
                }



                $repairItems = [];
                $repairItems['repair_id'] = $orderInsert->id;
                $repairItems['item_type'] = $type;
                $repairItems['cover_by_warranty'] = isset($services['cover'][$i]) ? $services['cover'][$i] : 0;
                $repairItems['item_id']  = $item_id = $services['id'][$i];
                $repairItems['qty'] = $quantity = $services['qty'][$i];
                $repairItems['price'] = $price = $services['price'][$i];
                $sale_total_price = $quantity*$price;

                if($type == "service")
                {
                    $repairItems['item_type'] = 1;
                    $item = Serviceing::find($item_id);
                    $item_title = $item->title;
                    $total_service += $sale_total_price;
                }else{
                    $repairItems['item_type'] = 0;
                    $item = Item::find($item_id);
                    $item_title = $item->item_name;
                    $total_item_price += $sale_total_price;

                    $this->stock_report($item_id, $quantity, 'sale', $saleDate);
                    $this->stockOut($item_id, $quantity);
                }

                $saleLedgerDescription = empty($saleLedgerDescription) ? $item_title."(".$quantity.")" : $saleLedgerDescription." & ".$item_title."(".$quantity.")";

                $total_amount += $sale_total_price;

                $repairItemList = RepairItem::create($repairItems);

                if($repairItemList)
                {
                    array_push($sale_product, $item_id);
                    array_push($sale_product_qty, $quantity);
                    $insert++;
                }

            }


            if($insert == $count_product)
            {
                $repair = [];

                $discount = $inputs['discount'];
                $repair['total_service_price'] = $total_service;
                $repair['total_item_price'] = $total_item_price;
                $repair['paid'] = $inputs['paid_amount'] = $inputs['paid'];
                $repair['discount'] = $discount;
                $repair['amount_to_pay'] = $amount_to_pay = $total_amount-$discount;
                $repair['due'] = $amount_to_pay-$repair['paid'];

                $orderInsert->update($repair);

                $inputs['payment_method_id'] = 1;
                $inputs['date'] = $inputs['entry_date'];

                $save_transaction = Transactions::where('repair_order_id', $orderInsert->id)->first();
                $save_transaction->supplier_id = $inputs['customer_id'];
                $save_transaction->cash_or_bank_id = $inputs['account_type_id'];
                $save_transaction->date = $saleDate;
                $save_transaction->amount = $total_amount;
                $save_transaction->notation = 'Repair Invoice';
                $save_transaction->transaction_method = $inputs['transaction_method'] = "Repairs";
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


                $trans_details = TransactionDetail::where('transaction_id', $save_transaction->id)->get();
                foreach ($trans_details as $value)
                {
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

                    if($sharer)
                    {
                        $check = [];
                        $check['sharer_name'] = $inputs['sharer_name'];
                        $check['account_type_id'] = $sharer->account_type_id;
                        $check['amount'] = $amount;
                        $this->updateSharerBalance($check);
                    }


                    $value->delete();
                }

                $this->create_sale_transaction($account, $save_transaction, $orderInsert, $discount, $repair['due'], $sharer, $total_amount, $inputs, $saleLedgerDescription);


                /*
                 * TODO: Create Transaction History for Sales
                 */

                $status = ['type' => 'success', 'message' => trans('common.repair_order_update_successfully')];
            }else{

                for($i=0; $i<count($sale_product); $i++) {

                    $this->stock_report($sale_product[$i], $sale_product_qty[$i], 'sale return', $saleDate);
                    $this->stockIn($sale_product[$i], $sale_product_qty[$i],'sale Return');
                    //  $this->createStockHistory($sale_product[$i], $sale_product_qty[$i], 'Stock In', $p_stock, $c_stock);
                }

                $status = ['type' => 'danger', 'message' => trans('common.product_out_of_stock')];
                $orderInsert->delete();
            }


        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('sale.unable_to_save')];
            DB::rollBack();
        }

        DB::commit();

        if($status['type'] == 'success')
        {
            return redirect()->route('member.repair_orders.show', $orderInsert->id);
        }else{

            return redirect()->back()->with('status', $status);
        }

    }

    public function rules($id="")
    {
        $rules = [
            'entry_date' => 'required',
            'cash_or_bank_id' => 'required',
            'product_name' => 'required',
//            'payment_method_id' => 'required',
            'customer_phone' => 'required',
            'customer_id' => 'required',
            'amount_to_pay' => 'required',
            'paid_amount' => 'required',
            'sub_total' => 'required',
            'due' => 'required',
            'discount' => 'required',
            'estimate_delivery_date' => 'required',
            "defect_id"    => "required|array|min:1",
            "defect_id.*"  => "required|numeric",
            "services"    => "required|array|min:1",
//            "services.*"  => "required|numeric",
//            "qty"    => "required|array|min:1",
//            "price"    => "required|array|min:1",
//            "price.*"  => "required|numeric",
        ];


        if(!$id)
        {
            $rules['repair_type'] = 'required';
        }

        return $rules;
    }

}
