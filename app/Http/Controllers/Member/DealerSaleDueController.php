<?php

namespace App\Http\Controllers\Member;

use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\DueCollectionHistory;
use App\Models\DealerSale;
use App\Models\SupplierOrCustomer;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealerSaleDueController extends SalesController
{
    public function due_list()
    {
        $data['modal'] = DealerSale::authMember()->authCompany()->where('due', '>', 0)->paginate(15);

        return view('member.sales.due_list', $data);
    }

    public function due_payment($id)
    {
        $sale = $data['sales'] = DealerSale::where('id', $id)->where('due','>',0)->first();
        $data = $this->company($data);
        if($sale)
        {
            return view('member.sales.due_payment', $data);
        }else{
            $status = [
                'type' => 'danger',
                'message' => "DealerSale ID: ".$id.", This DealerSale don't have any due"
            ];
            return redirect()->back()->with('status', $status);
        }

    }

    public function receive_due_payment(Request $request, $id)
    {
        $saleId = DealerSale::find($id);
        $amount = create_float_format($request->due_amount);
        $collection_date = db_date_format($request->date);

        if($saleId && $amount <= $saleId->due)
        {
            $save = new DueCollectionHistory();
            $inputs = [];
            $inputs['inventory_type'] = 'DealerSale';
            $inputs['inventory_type_id'] = $saleId->id;
            $inputs['sharer_id'] = $saleId->customer_id;
            $inputs['amount'] = $amount;
            $inputs['collection_date'] = $collection_date;
            $save->create($inputs);

            $sale = [];
            $sale['due'] = $saleId->grand_total-($saleId->paid_amount+$amount);
            $sale['paid_amount'] = $saleId->paid_amount+$amount;

            $sale['due'] = create_float_format($sale['due']);
            $sale['paid_amount'] = create_float_format($sale['paid_amount']);
            $saleId->update($sale);


            $account = CashOrBankAccount::find($saleId->cash_or_bank_id);
            $sharer = SupplierOrCustomer::find($saleId->customer_id);
            $inputs['sharer_name'] = $sharer ? $sharer->name : '';


            // Update Cash and Bank Account Balance
            $this->bankAccountBalanceUpdate("Sales", $account, $amount);

            if(isset($request->supplier_id)) {
                $this->sharerBalanceUpdate("Sales", $sharer, $amount);
            }

            $inputs['transaction_code'] = transaction_code_generate();
            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->supplier_id = $saleId->customer_id;
//            $save_transaction->sale_id = $saleId->id;
            $save_transaction->cash_or_bank_id = $saleId->cash_or_bank_id;
            $save_transaction->date = $inputs['date'] = $collection_date;
            $save_transaction->amount = $amount;
            $save_transaction->notation = "";
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Sales";
            $save_transaction->save();

            $inputs['against_account_type_id'] = $sharer->account_type->id;
            $inputs['against_account_name'] = $sharer->account_type->display_name;
            $inputs['account_name'] = $account->account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $amount;
            $inputs['account_type_id'] = $account->account_type->id;
            $inputs['transaction_type'] = 'dr';
            $inputs['payment_method_id'] = $saleId->payment_method_id;
            $inputs['description'] = "DealerSale due payment"." DealerSale Id : ".$saleId->id;
            $this->createDebitAmount($inputs);

            $inputs['account_type_id'] = $sharer->account_type->id;
            $inputs['account_name'] = $sharer->account_type->display_name;
            $inputs['against_account_name'] = $account->account_type->display_name;
            $inputs['against_account_type_id'] = $account->account_type->id;
            $inputs['transaction_type'] = 'cr';
            $this->createCreditAmount($inputs);

            $status = ['type' => 'success', 'message' => 'DealerSale Due Payment done Successfully'];
        }else{
            $status = ['type' => 'danger', 'message' => 'Unable to find due payment sale Id'];
        }

        return redirect()->route('member.sales.due_list')->with('status', $status);
    }


}
