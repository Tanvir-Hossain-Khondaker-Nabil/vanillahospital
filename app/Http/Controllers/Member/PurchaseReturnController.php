<?php

namespace App\Http\Controllers\Member;

use App\DataTables\PurchaseReturnDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ReturnPurchase;
use App\Models\Stock;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PurchaseReturnController extends Controller
{
    use FileUploadTrait, TransactionTrait, StockTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseReturnDataTable $dataTable)
    {
        return $dataTable->render('member.purchase.return_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {

//        $data['purchase'] = Purchase::find($id);

        $data['purchase'] = Purchase::whereHas('purchase_returns', function ($query) use ($code) {
            $query->where('return_code', $code);
        })->get();

        if(count($data['purchase'])>0)
            return view('member.purchase.show_return', $data);
        else
            return redirect()->route('member.purchase_return.index');
    }


    public function view_returns($id, $code)
    {
//        $data['purchase'] = Purchase::whereHas('purchase_returns', function ($query) use ($code, $id) {
//            $query->where('return_code', '=',$code)->where('purchase_id', '=',$id);
//        })->first();
//
        $data['purchase'] = Purchase::join('return_purchases','return_purchases.purchase_id','=','purchases.id')
            ->join('items','items.id','=','return_purchases.item_id')
            ->where('return_code', '=',$code)
            ->where('purchase_id', '=',$id)->get();

        if(count($data['purchase'])>0)
            return view('member.purchase.return_purchase_show', $data);
        else
            return redirect()->route('member.purchase_return.index');
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['modal'] = $purchase = Purchase::findOrFail($id);

        $accounts = TransactionDetail::whereHas('transaction', function($q) use($id){
            $q->where('purchase_id', $id);
        })->pluck('account_type_id')->toArray();

        $purchaseItems = $purchase->purchase_details->pluck('item_id');
        $data['memo_no'] = $purchase->memo_no;
        $data['chalan_no'] = $purchase->chalan;
        $data['suppliers'] = SupplierOrCustomer::onlySuppliers()->latest()->pluck('name', 'id');
        $data['products'] = Item::whereIn('id', $purchaseItems)->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::whereIn('account_type_id', $accounts)->latest()->pluck('title', 'account_type_id');

        return view('member.purchase.return_latest', $data);
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
        $purchaseId = Purchase::findOrFail($id);

        $this->validate($request, $this->validationRules());

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = $this->fileUpload($file, '/file/', null);

            if (!$upload)
            {
                $status = ['type' => 'danger', 'message' => trans('common.image_must_be').' JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }
            $purchase['file'] = $upload;

            $purchaseId->update($purchase);
        }


        $product = $request->product_id;
        $unit = $request->unit;
        $qty = $request->qty;
        $price = $request->price;
        $description = $request->description;
        $return_qty = $request->return_qty;
        $return_price = $request->return_price;
        $account_type_id = $request->account_type_id;
        $return_date = db_date_format($request->date);

        $against_account_type = AccountType::find($account_type_id);

        $msg = $failure = $ledgerDescription = '';
        $purchaseReturn = [];
        $purchaseReturn['purchase_id'] = $id;
        $purchaseReturn['return_date'] = $return_date;
        $purchaseReturn['return_code'] = $code = return_code_generate("P");

        $total_return_price = 0;
        $return_count = 0;
        for($i=0; $i<count($product); $i++){

            $purchaseDetails = PurchaseDetail::where('purchase_id', $id)
                ->where('item_id', $product[$i])
                ->first();

            $item = Item::find($product[$i]);
            $purchaseReturn['item_id'] = $item_id = $product[$i];

            $return_item = ReturnPurchase::where('item_id', $item_id)
                ->where('purchase_id', $id)
                ->sum('return_qty');

            $purchaseReturn['unit'] = $item->unit;
            $purchaseReturn['qty'] = $purchaseDetails->qty;
            $purchaseReturn['price'] = $purchaseDetails->price;
            $purchaseReturn['return_qty'] = $quantity = $return_qty[$i];
            $purchaseReturn['return_price'] = $return_price[$i];
            $purchaseReturn['description'] = $description[$i];
            $purchaseReturn['fine_price'] = $purchaseDetails->price - $return_price[$i];

            if( ($purchaseDetails->qty >= ($return_item+$return_qty[$i])) &&  isset($quantity) && $quantity>0)
            {
                if($purchaseDetails->price >= $return_price[$i])
                {
                    $purchaseDetails->purchase_status = 'return';
                    $purchaseDetails->update();

                    $ledgerDescription = empty($ledgerDescription) ? $item->item_name."(".$quantity.$item->unit."x".$return_price[$i].")" : $ledgerDescription." & ".$item->item_name."(".$quantity.$item->unit."x".$return_price[$i].")" ;

                    $return = ReturnPurchase::create($purchaseReturn);
                    $this->stock_report($product[$i], $return_qty[$i], 'purchase return', $purchaseId->date);
                    $this->stockOut($product[$i], $return_qty[$i],'Purchase Return');
                    //  $this->createStockHistory($product[$i], $return_qty[$i],'Purchase Return');
                    $msg = true;
                    $return_count++;

                    $returnID[] = $return->id;
                }else{
                    $msg = false;
                    $failure .= trans('common.product').": ".$item->item_name." ".trans('purchase.price_cant_be_bigger_than_sale_price')." | ";
                }

            }else{
                $msg = false;
                $failure .= trans('common.product').": ".$item->item_name." ".trans('purchase.qty_price_zero_or_return_already_completed')." | ";
            }

            $total_return_price += ($quantity*$return_price[$i]);



        }


        if($total_return_price > $purchaseId->total_price)
        {
            $msg = false;
            $failure = trans('purchase.return_amount_cant_be_bigger_than_bill_amount');
        }


        if($msg == true || $return_count > 0)
        {

            $purchase = [];
            if( $purchaseId->due_amount > 0 && $purchaseId->due_amount >= $total_return_price)
            {
                $purchase['due_amount'] = $purchaseId->due_amount-$total_return_price;
            }

            $purchase['total_price'] = $purchaseId->total_price-$total_return_price;
            $purchaseId->update($purchase);


            $sharer = SupplierOrCustomer::find($purchaseId->supplier_id);
            $inputs['sharer_name'] = $sharer->name;


            $inputs['transaction_code'] = transaction_code_generate();
            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->supplier_id = $purchaseId->supplier_id;
            $save_transaction->purchase_id = $purchaseId->id;
            $save_transaction->cash_or_bank_id = $purchaseId->cash_or_bank_id;
            $save_transaction->date = $inputs['date'] = $return_date;
            $save_transaction->amount = $total_return_price;
            $save_transaction->notation = $request->notation;
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Purchase Return";
            $save_transaction->save();


            foreach ($returnID as $value)
            {
                ReturnPurchase::find($value)->update(['transaction_id'=>$save_transaction->id]);
            }

            $inputs['payment_method_id'] = $purchaseId->payment_method_id;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $total_return_price;
            $inputs['description'] = "Return: Purchase Id # ".$purchaseId->id.", Product: ".$ledgerDescription;

            $account_type = AccountType::where('display_name', 'Purchase')->first();

            $inputs['transaction_type'] = 'cr';
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account_type->display_name;
            $inputs['against_account_type_id'] = $account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $this->createCreditAmount($inputs);

            $inputs['transaction_type'] = 'dr';
            $inputs['account_type_id'] = $account_type_id;
            $inputs['account_name'] = $against_account_type->display_name;
            $inputs['against_account_type_id'] = $account_type->id;
            $inputs['against_account_name'] = $account_type->display_name;
            $this->createDebitAmount($inputs);


            $status = ['type' => 'success', 'message' => trans('purchase.purchase_return_done_successfully')];

            return redirect()->route('member.purchase_return.view_returns', [ 'id'=>$id, 'code'=>$code] )->with('status', $status);
        }else{
            $status = ['type' => 'danger', 'message' => trans('purchase.unable_to_product_return').'  | '.$failure];
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
        //
    }


    private function validationRules()
    {
        $validator = [
            "account_type_id" => "required",
            "date" => "required|date_format:m/d/Y",
            "product_id" => "required|array|min:1",
            "product_id.*" => "required|numeric|min:1",
            "return_qty" => "required|array",
            "return_qty.*" => "nullable|numeric",
            "return_price" => "required|array",
            "return_price.*" => "nullable|numeric",
        ];

        return $validator;

    }
}
