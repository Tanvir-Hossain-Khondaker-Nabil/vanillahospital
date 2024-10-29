<?php

namespace App\Http\Controllers\Member;

use App\DataTables\SaleReturnDataTable;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleReturnController extends SalesController
{
    public function sales_return($id)
    {
        $data['model'] = Sale::findOrFail($id);

        $accounts = TransactionDetail::whereHas('transaction', function($q) use($id){
            $q->where('sale_id', $id);
        })->pluck('account_type_id')->toArray();

        $data['delivery_types'] = DeliveryType::active()->get()->pluck('display_name', 'id');
        $data['customers'] = SupplierOrCustomer::onlyCustomers()->authCompany()->latest()->get()->pluck('name_phone', 'id')->toArray();
        $data['products'] = Item::whereHas('category', function ($query){
            $query->where('name','!=', 'shopping_bags');
        })->authCompany()->orderAsc()->latest()->pluck('item_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name', 'id');
        $data['banks'] = CashOrBankAccount::authCompany()->whereIn('account_type_id', $accounts)->pluck('title', 'account_type_id');

        $data['bags'] = Item::whereHas('category', function ($query){
            $query->where('name', 'shopping_bags');
        })->authCompany()->latest()->get();

        return view('member.sales.return', $data);
    }

    public function sales_return_update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $this->validate($request, $this->validationRules());

        $product = $request->product_id;
        $unit = $request->unit;
        $qty = $request->qty;
        $price = $request->price;
        $return_qty = $request->return_qty;
        $return_price = $request->return_price;
        $description = $request->description;
        $account_type_id = $request->account_type_id;
        $sale_return_date = db_date_format($request->date);

        $against_account_type = AccountType::find($account_type_id);

        $msg = $failure = $ledgerDescription = '';
        $saleReturn = [];
        $saleReturn['sale_id'] = $id;
        $saleReturn['return_code'] = $code = return_code_generate("S");

        $total_return_price = 0;
        $return_count = 0;
        for($i=0; $i<count($product); $i++){

            $saleDetails = SaleDetails::where('sale_id', $id)
                ->where('item_id', $product[$i])
                ->first();

            $item = Item::find($product[$i]);
            $saleReturn['item_id'] = $item_id = $product[$i];

            $return_item = SaleReturn::where('item_id', $item_id)
                ->where('sale_id', $id)
                ->sum('return_qty');

            $saleReturn['unit'] = $item->unit;
            $saleReturn['qty'] = $qty[$i];
            $saleReturn['return_qty'] = $return_quantity = $return_qty[$i];
            $saleReturn['price'] = $price[$i];
            $saleReturn['return_price'] = $return_price[$i];
            $saleReturn['return_date'] = $sale_return_date;
            $saleReturn['description'] = $description[$i];


            if( ($saleDetails->qty >= ($return_item+$return_qty[$i])) &&  isset($return_quantity) && $return_quantity>0)
            {
                if($saleDetails->price >= $return_price[$i])
                {
                    $saleDetails->sale_status = 'return';
                    $saleDetails->update();


                    $ledgerDescription = empty($ledgerDescription) ? $item->item_name."(".$return_quantity.$item->unit."x".$return_price[$i].")" : $ledgerDescription." & ".$item->item_name."(".$return_quantity.$item->unit."x".$return_price[$i].")" ;

                    $return = SaleReturn::create($saleReturn);
                    $this->stock_report($product[$i], $return_qty[$i], 'sale return', $sale->date);
                    $this->stockIn($product[$i], $return_qty[$i],'sale Return');
                    //  $this->createStockHistory($product[$i], $return_qty[$i],'sale Return', $p_stock, $c_stock);
                    $msg = true;
                    $return_count++;

                    $returnID[] = $return->id;
                }else{
                    $msg = false;
                    $failure .= " Product: ".$item->item_name." price can't be bigger than sale price | ";
                }

            }else{
                $msg = false;
                $failure .= " Product: ".$item->item_name." Qty/Price Zero(0) or return already completed | ";
            }

            $total_return_price += ($return_quantity*$return_price[$i]);
        }

        if($total_return_price > $sale->grand_total)
        {
            $msg = false;
            $failure = "Sale Return amount can't be bigger than Bill Amount";
        }

        if($msg == true || $return_count > 0)
        {
            $saleUpdate = [];
            if( $sale->due > 0 && $sale->due >= $total_return_price)
            {
                $saleUpdate['due'] = $sale->due-$total_return_price;
            }

            $sale->update($saleUpdate);

            $sharer = SupplierOrCustomer::find($sale->customer_id);
            $inputs['sharer_name'] = $sharer ? $sharer->name : '';
            $inputs['transaction_code'] = transaction_code_generate();
            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->supplier_id = $sale->customer_id;
            $save_transaction->sale_id = $sale->id;
            $save_transaction->cash_or_bank_id = $sale->cash_or_bank_id;
            $save_transaction->date = $inputs['date'] = $sale_return_date;
            $save_transaction->amount = $total_return_price;
            $save_transaction->notation = $request->notation;
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Sale Return";
            $save_transaction->save();

            foreach ($returnID as $value)
            {
                SaleReturn::find($value)->update(['transaction_id'=>$save_transaction->id]);
            }


            $inputs['payment_method_id'] = $sale->payment_method_id;
            $account_type = AccountType::where('display_name', 'Sales')->first();

            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $total_return_price;
            $inputs['description'] = "Return: Sale Id # ".$sale->id.", Product:".$ledgerDescription;


            $inputs['transaction_type'] = 'dr';
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account_type->display_name;
            $inputs['against_account_type_id'] = $account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $this->createDebitAmount($inputs);

            $inputs['transaction_type'] = 'cr';
            $inputs['account_type_id'] = $account_type_id;
            $inputs['account_name'] = $against_account_type->display_name;
            $inputs['against_account_type_id'] = $account_type->id;
            $inputs['against_account_name'] = $account_type->display_name;
            $this->createCreditAmount($inputs);


            $status = ['type' => 'success', 'message' => trans('common.sale_return_done_successfully')];

            return redirect()->route('member.sales.view_return', [ 'id'=>$id, 'code'=>$code] )->with('status', $status);
        }else{
            $status = ['type' => 'danger', 'message' => trans('common.unable_to_product_return').' | '.$failure];
            return redirect()->back()->with('status', $status);
        }
    }

    public function sale_return_view($id, $code)
    {
        $data['sale'] = Sale::join('sales_return','sales_return.sale_id','=','sales.id')
            ->join('items','items.id','=','sales_return.item_id')
            ->where('return_code', '=',$code)
            ->where('sale_id', '=',$id)->get();

        if(count($data['sale'])>0)
            return view('member.sales.view_return', $data);
        else
            return redirect()->route('member.sales.sales_return_list');
    }

    public function sales_return_list(SaleReturnDataTable $dataTable)
    {
        return $dataTable->render('member.sales.sale_return_list');
    }



    private function validationRules()
    {
        $validator = [
            "account_type_id" => "required",
            "date"          => "required|date_format:m/d/Y",
            "product_id"    => "required|array|min:1",
            "product_id.*"  => "required|numeric|min:1",
            "return_qty"    => "required|array",
            "return_qty.*"  => "nullable|numeric",
            "return_price"    => "required|array",
            "return_price.*"  => "nullable|numeric",
        ];

        return $validator;
    }
}
