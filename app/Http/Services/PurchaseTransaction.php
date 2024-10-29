<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/20/2022
 * Time: 5:45 PM
 */

namespace App\Http\Services;

use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;

class PurchaseTransaction
{
    use TransactionTrait;
    
    public $purchase;

    public function __construct($id)
    {
       $this->purchase =  $id;
    }


    public function updateTransactions()
    {
        
        $deletedPurchase = "";

        $value = Purchase::findOrFail($this->purchase);

        $save_transaction = Transactions::where('purchase_id', $value->id)->first();
        $account = CashOrBankAccount::find($value->cash_or_bank_id);

        if($save_transaction)
        {
            $ledgerDescription = "" . $save_transaction->notation;
            $ledgerDescription = $ledgerDescription." Purchase Id : ".$this->purchase.", Products: ";

            foreach ($value->purchase_details as $detail) {
                $item = Item::find($detail->item_id);
                $price = create_float_format($detail->price);
                $quantity = $detail->qty;

                $ledgerDescription = empty($ledgerDescription) ? $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")" : $ledgerDescription . " & " . $item->item_name . "(" . $quantity . $item->unit . "x" . $price . ")";

            }

            $inputs = [];
            $inputs['transaction_code'] = $save_transaction->transaction_code;
            $inputs['amount'] = $save_transaction->amount;
            $inputs['customer_id'] = $save_transaction->supplier_id;
            $inputs['cash_or_bank_id'] = $save_transaction->transaction_code;
            $inputs['notation'] = $save_transaction->notation;
            $inputs['transaction_method'] = $save_transaction->transaction_method;
            $inputs['payment_method_id'] = $value->payment_method_id;
            $inputs['sub_total'] = $value->total_price;
            $inputs['paid_amount'] = $value->paid_amount;
            $inputs['transport_cost'] = $value->transport_cost;
            $inputs['total_amount'] = $value->amt_to_pay;
            $inputs['advance_amount'] = $value->advance_amount;
            $inputs['amount_to_pay'] = $value->amt_to_pay;
            $inputs['bank_charge'] = $value->bank_charge;
            $inputs['unload_cost'] = $value->unload_cost;
            $inputs['date'] = $value->date;
            $inputs['company_id'] = $value->company_id;
            $inputs['member_id'] = $value->member_id;
            $inputs['created_by'] = $value->created_by;


            if (isset($value->supplier_id)) {
                $sharer = SupplierOrCustomer::find($value->supplier_id);
                $inputs['sharer_name'] = $sharer ? $sharer->name : '';
            } else {
                $sharer = null;
                $inputs['sharer_name'] = '';
            }

            $trans_details = TransactionDetail::where('transaction_id', $save_transaction->id)->get();

            foreach ($trans_details as $val) {
                $val->delete();
            }


            $total_entry = $this->create_transaction($account, $save_transaction, $value->id, $value->total_discount, $value->due_amount, $sharer, $inputs['total_amount'], $inputs, $ledgerDescription);
            
            
            $trans_details_count = TransactionDetail::where('transaction_id', $save_transaction->id)->count();

            if($trans_details_count != $total_entry)
                    $this->updateTransactions();

        }else{
            $deletedPurchase .= $value->id.", ";
        }
    }

    protected function create_transaction($account, $save_transaction, $purchaseId, $discount, $due, $sharer, $total_amount, $inputs, $ledgerDescription)
    {
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
        $inputs['description'] = $ledgerDescription;
        $this->createCreditAmount($inputs);
        $total_entry = 1;

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
            $inputs['description'] = "Discount & "." Purchase Id : ".$purchaseId.", Purchase product: ".$ledgerDescription;
            $this->createDebitAmount($inputs);
            $total_entry++;
        }


        if($due>0)
        {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] =  $sharer->account_type->display_name;
            $dr_against_name = (!empty($dr_against_name) ? $dr_against_name." & " : null).$sharer->account_type->display_name;
            $inputs['against_account_type_id']  = $against_account_type->account_type_id;
            $inputs['against_account_name']  = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $due;
            $inputs['transaction_type'] = 'cr';
            $this->createDebitAmount($inputs);
            $total_entry++;
        }

        if(isset($inputs['advance_amount']) && $inputs['advance_amount']>0)
        {
            $inputs['account_type_id'] = $sharer->account_type_id;
            $inputs['account_name'] = $sharer->account_type->display_name;
            $inputs['against_account_type_id']  = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['advance_amount'];
            $inputs['transaction_type'] = 'dr';
            $this->createDebitAmount($inputs);
            $total_entry++;
        }

//        if(config('pos.purchase_others_cost_manage_ledger'))
//        {

            if($inputs['transport_cost']>0)
            {
                $account_type = AccountType::where('name', 'transport_cost')->first();
                $inputs['account_type_id'] = $account_type->id;
                $inputs['account_name'] = $account_type->display_name;
                $inputs['against_account_type_id']  = $account->account_type_id;
                $inputs['against_account_name'] = $account->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['transaction_id'] = $save_transaction->id;
                $inputs['amount'] = $inputs['transport_cost'];
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
                $total_entry++;
            }
    
            if($inputs['unload_cost']>0)
            {
                $account_type = AccountType::where('name', 'unload_cost')->first();
                $inputs['account_type_id'] = $account_type->id;
                $inputs['account_name'] = $account_type->display_name;
                $inputs['against_account_type_id']  = $account->account_type_id;
                $inputs['against_account_name'] = $account->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['transaction_id'] = $save_transaction->id;
                $inputs['amount'] = $inputs['unload_cost'];
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
                $total_entry++;
            }
    
            if($inputs['bank_charge']>0)
            {
                $account_type = AccountType::where('name', 'bank_charge')->first();
                $inputs['account_type_id'] = $account_type->id;
                $inputs['account_name'] = $account_type->display_name;
                $inputs['against_account_type_id']  = $account->account_type_id;
                $inputs['against_account_name'] = $account->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['transaction_id'] = $save_transaction->id;
                $inputs['amount'] = $inputs['bank_charge'];
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
                $total_entry++;
            }
    
            $total_amount = $inputs['amount_to_pay']-$inputs['bank_charge']-$inputs['unload_cost']-$inputs['transport_cost'];
//        }else{
//            $total_amount = $inputs['amount_to_pay'];
//        }

        $inputs['account_type_id'] = $against_account_type->id;
        $inputs['account_name'] = $against_account_type->display_name;
        $inputs['against_account_type_id'] = null;
        $inputs['against_account_name'] = $dr_against_name;
        $inputs['amount'] = $total_amount;
        $inputs['transaction_type'] = 'dr';
        $this->createDebitAmount($inputs);

        $total_entry++;

        return  $total_entry;
    }


}
