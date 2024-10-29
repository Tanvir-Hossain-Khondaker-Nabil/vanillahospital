<?php

namespace App\Http\Services;

use App\Http\Traits\TransactionTrait;

class ExpenseRequisitionTransaction
{
    use TransactionTrait;

    public $expenseId;

    public function __construct($id)
    {
        $this->expenseId =  $id;
    }


    public function updateTransactions()
    {

        $value = Sale::findOrFail($this->sale);

        $save_transaction = Transactions::where('sale_id', $value->id)->first();
        $account = CashOrBankAccount::find($value->cash_or_bank_id);

        $saleLedgerDescription = "" . $save_transaction->notation;
        $saleLedgerDescription = $saleLedgerDescription." Sale Id : ".$this->sale.", Products: ";

        foreach ($value->sale_details as $detail) {
            $item = Item::find($detail->item_id);
            $price = create_float_format($detail->total_price / $detail->qty, 2);
            $quantity = $detail->qty;

            $saleLedgerDescription = empty($saleLedgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")" : $saleLedgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")";

        }


        $inputs = [];
        $inputs['transaction_code'] = $save_transaction->transaction_code;
        $inputs['amount'] = $save_transaction->amount;
        $inputs['customer_id'] = $save_transaction->transaction_code;
        $inputs['cash_or_bank_id'] = $value->cash_or_bank_id;
        $inputs['notation'] = $save_transaction->notation;
        $inputs['transaction_method'] = $save_transaction->transaction_method;
        $inputs['payment_method_id'] = $value->payment_method_id;
        $inputs['total_price'] = $value->total_price;
        $inputs['paid_amount'] = $value->paid_amount;
        $inputs['transport_cost'] = $value->transport_cost;
        $inputs['total_amount'] = $value->amount_to_pay;
        $inputs['amount_to_pay'] = $value->amount_to_pay;
        $inputs['shipping_charge'] = $value->shipping_charge;
        $inputs['unload_cost'] = $value->unload_cost;
        $inputs['date'] = $value->date;
        $inputs['company_id'] = $value->company_id;
        $inputs['member_id'] = $value->member_id;
        $inputs['created_by'] = $value->created_by;


        if (isset($value->customer_id)) {
            $sharer = SupplierOrCustomer::find($value->customer_id);
            $inputs['sharer_name'] = $sharer ? $sharer->name : '';
        } else {
            $sharer = null;
            $inputs['sharer_name'] = '';
        }

        $trans_details = TransactionDetail::where('transaction_id', $save_transaction->id)->get();

        foreach ($trans_details as $val) {
            $val->delete();
        }


        $total_entry = $this->create_transaction($account, $save_transaction, $value->id, $value->total_discount, $value->due, $sharer, $inputs['total_price'], $inputs, $saleLedgerDescription);
        $trans_details_count = TransactionDetail::where('transaction_id', $save_transaction->id)->count();

        if($trans_details_count != $total_entry)
            $this->updateTransactions();
    }


    protected function create_transaction($account, $save_transaction, $saleId, $discount, $due, $sharer, $total_amount, $inputs, $saleLedgerDescription)
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
        $inputs['description'] = $saleLedgerDescription;
        $this->createDebitAmount($inputs);
        $total_entry =1;

        $dr_against_name = '';
        if($inputs['paid_amount']>0)
        {
            $dr_against_name = $account->account_type->display_name;
        }


        if($discount>0)
        {
            $account_type = AccountType::where('name', 'discount')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $discount;
            $inputs['transaction_type'] = 'dr';
            $inputs['description'] = "Discount & "." Sale Id : ".$saleId.", Sale product: ".$saleLedgerDescription;
            $this->createDebitAmount($inputs);
            $total_entry++;
        }

        if( isset($inputs['shipping_charge']) && $inputs['shipping_charge']>0)
        {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['shipping_charge'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = " Sale Id : ".$saleId.", Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
            $total_entry++;
        }

        if($inputs['unload_cost']>0)
        {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id']  = $against_account_type->account_type_id;
            $inputs['against_account_name']  = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['unload_cost'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = "Labor cost & Sale Id : ".$saleId.", Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
            $total_entry++;
        }

        if($inputs['transport_cost']>0)
        {
            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id']  = $against_account_type->account_type_id;
            $inputs['against_account_name']  = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['transport_cost'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = "Transport Cost & Sale Id : ".$saleId.", Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
            $total_entry++;
        }

        if($due>0 && !empty($inputs['sharer_name']))
        {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] =  $sharer->account_type->display_name;
            $dr_against_name = (!empty($dr_against_name) ? $dr_against_name." & " : null).$sharer->account_type->display_name;
            $inputs['against_account_type_id']  = $against_account_type->account_type_id;
            $inputs['against_account_name']  = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $due;
            $inputs['transaction_type'] = 'dr';
            $inputs['description'] = " Sale Id : ".$saleId.", Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
            $total_entry++;
        }


        $inputs['account_type_id'] = $against_account_type->id;
        $inputs['account_name'] = $against_account_type->display_name;
        $inputs['against_account_type_id'] = null;
        $inputs['amount'] = $total_amount;
        $inputs['against_account_name'] = $dr_against_name;

        $inputs['transaction_type'] = 'cr';
        $this->createCreditAmount($inputs);
        $total_entry++;

        return  $total_entry;

    }
}
