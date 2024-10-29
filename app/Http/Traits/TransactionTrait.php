<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/24/2019
 * Time: 10:50 AM
 */

namespace App\Http\Traits;


use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountHeadsBalanceHistory;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\JournalEntryDetail;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;

trait TransactionTrait
{
    use AccountHeadDayWiseBalanceTrait, AccountHeadBalanceHistoryTrait, SharerTrait;
    /**
     * @param array $inputs
     * @return TransactionDetail
     */

    public function createDebitAmount($inputs = array())
    {

        $save_transaction_details = new TransactionDetail();

//        $type = ($inputs['transaction_method'] == 'Income' || $inputs['transaction_method'] == 'Transfer') ? 'Debited to' : '';
//        $type = (empty($type) && $inputs['transaction_method'] == 'Expense') ? 'Credited from' : $type;

        $type = "Debited to";
        $save_transaction_details->date = isset($inputs['date']) ? db_date_format($inputs['date']) : Carbon::today();;
        $save_transaction_details->amount = $inputs['amount'];
        $save_transaction_details->transaction_id = $inputs['transaction_id'];
        $save_transaction_details->account_type_id = $inputs['account_type_id'];
        $save_transaction_details->against_account_type_id = isset($inputs['against_account_type_id']) ? $inputs['against_account_type_id'] : null;
        $save_transaction_details->against_account_name = isset($inputs['against_account_name']) ? $inputs['against_account_name'] : null;
        $save_transaction_details->description = $inputs['description'];
        $save_transaction_details->short_description = $inputs['account_name'].' '.$type." ".$inputs['to_account_name'];
        $save_transaction_details->short_description = !empty($inputs['sharer_name']) ? $save_transaction_details->short_description." by ".$inputs['sharer_name']: $save_transaction_details->short_description;
        $save_transaction_details->short_description = !empty($inputs['payment_method_name']) ? $save_transaction_details->short_description." using ".$inputs['payment_method_name']: $save_transaction_details->short_description;
        $inputs['transaction_method'] != 'Transfer' ? $save_transaction_details->account_type_id = $inputs['account_type_id'] : '';

       if( isset($inputs['transaction_type']) ){
           $transaction_type = $save_transaction_details->transaction_type = $inputs['transaction_type'];

       }else{
           // For Expense, it will type CR Else DR
           $transaction_type =  $save_transaction_details->transaction_type = ( $save_transaction_details->transaction_method == 'Income' || $inputs['transaction_method'] == 'Transfer')  ? 'dr' : 'cr';
       }

        $save_transaction_details->payment_method_id = $inputs['payment_method_id'];
        $save_transaction_details->save();

        $inputs['account_head_name'] = $inputs['account_name'];;
        $inputs['transaction_type'] = $transaction_type;
        $this->createAccountHeadBalanceHistory($inputs);
        $this->createAccountHeadDayWiseBalanceHistory($inputs);
        $this->updateAccountHeadBalanceByDate($inputs);
        $this->updateSharerBalance($inputs);
        $this->updateCashBankBalance($inputs);


        return $save_transaction_details;
    }

    /**
     * @param array $inputs
     * @return TransactionDetail
     */

    public function createCreditAmount($inputs = array())
    {
        $save_transaction_details = new TransactionDetail();

//        $type = $inputs['transaction_method'] == 'Expense' ? 'Debited to' : '';
//        $type = (empty($type) && ($inputs['transaction_method'] == 'Income' || $inputs['transaction_method'] == 'Transfer')) ? 'Credited from' : $type;
        $type = "Credited from";

        $save_transaction_details->date = isset($inputs['date']) ? db_date_format($inputs['date']) : Carbon::today();
        $save_transaction_details->amount = $inputs['amount'];
        $save_transaction_details->transaction_id = $inputs['transaction_id'];
        $save_transaction_details->account_type_id = $inputs['account_type_id'];
        $save_transaction_details->against_account_type_id = isset($inputs['against_account_type_id']) ? $inputs['against_account_type_id'] : null;
        $save_transaction_details->against_account_name = isset($inputs['against_account_name']) ? $inputs['against_account_name'] : null;
        $save_transaction_details->short_description = $inputs['to_account_name']." ".$type." ".$inputs['account_name'];
        $save_transaction_details->short_description = !empty($inputs['sharer_name']) ? $save_transaction_details->short_description." by ".$inputs['sharer_name']: $save_transaction_details->short_description;
        $save_transaction_details->short_description = !empty($inputs['payment_method_name']) ? $save_transaction_details->short_description." using ".$inputs['payment_method_name']: $save_transaction_details->short_description;
        $save_transaction_details->description = $inputs['description'];
        $inputs['transaction_method'] != 'Transfer' ? $save_transaction_details->account_type_id = $inputs['account_type_id'] : '';

        if(!isset($inputs['transaction_type'])) {
            // For Expense, it will type DR Else CR
            $transaction_type = $save_transaction_details->transaction_type = ($save_transaction_details->transaction_method == 'Income' || $inputs['transaction_method'] == 'Transfer') ? 'cr' : 'dr';
        }else{
            $transaction_type = $save_transaction_details->transaction_type = $inputs['transaction_type'];
        }

        $save_transaction_details->payment_method_id = $inputs['payment_method_id'];
        $save_transaction_details->save();

        $inputs['account_head_name'] = $inputs['account_name'];
        $inputs['transaction_type'] = $transaction_type;
        $this->createAccountHeadBalanceHistory($inputs);
        $this->createAccountHeadDayWiseBalanceHistory($inputs);
        $this->updateAccountHeadBalanceByDate($inputs);
        $this->updateSharerBalance($inputs);
        $this->updateCashBankBalance($inputs);

        return $save_transaction_details;
    }


    /**
     * @param $transaction_method
     * @param $sharer
     * @param $amount
     * @return true/False
     */
    public function sharerBalanceUpdate($transaction_method, $sharer, $amount)
    {
        if($transaction_method == 'Income')
        {
            if($sharer->customer_type=='supplier')
                $sharer->supplier_current_balance = $sharer->supplier_current_balance-$amount;
            else
                $sharer->customer_current_balance = $sharer->customer_current_balance-$amount;
        }

        if($transaction_method == 'Expense')
        {
            if($sharer->customer_type=='supplier')
                $sharer->supplier_current_balance = $sharer->supplier_current_balance+$amount;
            else
                $sharer->customer_current_balance = $sharer->customer_current_balance+$amount;
        }

        if($transaction_method == 'Purchases')
        {
            $sharer->supplier_current_balance = $sharer->supplier_current_balance-$amount;
        }

        if($transaction_method == 'Purchase Update')
        {
            $sharer->supplier_current_balance = $sharer->supplier_current_balance+$amount;
        }

        if($transaction_method == 'Sales')
        {
            $sharer->customer_current_balance = $sharer->customer_current_balance-$amount;
        }

        if($transaction_method == 'Sale Update')
        {
            $sharer->customer_current_balance = $sharer->customer_current_balance+$amount;
        }

       return $sharer->save();
    }


    /**
     * @param $transaction_method
     * @param $mainAccount
     * @param $total_amount
     * @return true/False
     */
    public function bankAccountBalanceUpdate($transaction_method, $mainAccount, $total_amount)
    {
        if($transaction_method == 'Income')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance+$total_amount;
        }

        if($transaction_method == 'Expense')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance-$total_amount;
        }

        if($transaction_method == 'Purchases')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance-$total_amount;
        }

        if($transaction_method == 'Sales')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance+$total_amount;
        }


        if($transaction_method == 'Purchase Update')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance-$total_amount;
        }

        if($transaction_method == 'Sale Update')
        {
            $mainAccount->current_balance  = $mainAccount->current_balance+$total_amount;
        }

        return $mainAccount->save();

    }


    public function updateCashBankBalance($inputs)
    {
        $CashOrBankAccountBalance = new CashOrBankAccount();
        $CashOrBankAccountBalance = $CashOrBankAccountBalance->where('account_type_id', $inputs['account_type_id'])->first();
        $cash = AccountHeadDayWiseBalance::where('account_type_id', $inputs['account_type_id'])->orderBy('date','desc')->first();

        if($CashOrBankAccountBalance && $cash){
//            $CashOrBankAccountBalance->current_balance = $inputs['amount'];
            $CashOrBankAccountBalance->current_balance = $cash->balance;
            $CashOrBankAccountBalance->update();
        }

        return 1;
    }

    public function set_transaction($inputs)
    {
        $data = [];
        $data['transaction_code'] = transaction_code_generate();

        // dd($inputs['transaction_method']);
        $save_transaction = new Transactions();
        $save_transaction->transaction_code = $data['transaction_code'];
        $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'] ?? null;
        $save_transaction->supplier_id = $inputs['supplier_id'] ?? null;
        $save_transaction->date = isset($inputs['date']) ? $inputs['date'] : Carbon::today();
        $save_transaction->amount = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
        $save_transaction->transaction_method = $inputs['transaction_method'] ?? "Initial";
        $save_transaction->save();

        return $save_transaction->id;
    }


    public function transactionRevertAmount($id)
    {
        $transaction = Transactions::find($id);

        foreach ( $transaction->transaction_details as $value){
            $account = AccountType::withTrashed()->where('id', $value->account_type_id)->first();

            $inputs = [];
            $inputs['account_type_id'] = $value->account_type_id;
            $inputs['account_head_name'] = $account->display_name;
            $inputs['amount'] =  $value->amount;
            $inputs['date'] =  $value->date;
            $inputs['transaction_type'] = $value->transaction_type == "dr" ? 'cr' : 'dr';
            $inputs['flag'] = "delete";
            $inputs['transaction_id'] = $id;

            $this->createAccountHeadBalanceHistory($inputs);
            $this->createAccountHeadDayWiseBalanceHistory($inputs);
            $this->updateSharerBalance($inputs);
            $this->updateCashBankBalance($inputs);
        }

    }

    public function delete_transaction_detail($transaction)
    {
        $trans_details = TransactionDetail::where('transaction_id', $transaction->id)->get();
        $this->transactionRevertAmount($transaction->id);

        foreach ($trans_details as $key => $value) {
            $data = [];
            $data[$key]['account_type_id'] = $value->account_type_id;
            $data[$key]['date'] = $value->date;

            $value->delete();
        }

        foreach ( $data as $value2) {
            $input = [];
            $input['account_type_id'] = $value2['account_type_id'];
            $input['date'] =  $value2['date'];
            $this->updateAccountHeadBalanceByDate($input);
        }
    }

    public function adjustmentBalance($inputs)
    {
        $data = [];
        $data['account_type_id'] = $inputs;
        $this->updateSharerBalance($data);
        $this->updateCashBankBalance($data);
    }

    public function transactionDestroy($id)
    {
        $modal = Transactions::findOrFail($id);

        $this->transactionRevertAmount($modal->id);

        if($modal->sale_id)
        {
            $sale = Sale::findOrFail($modal->sale_id);
            foreach($sale->sale_details as $value)
            {
                $this->stock_report($value->item_id, $value->qty, 'delete sale', $sale->date);
                $this->stockIn($value->item_id, $value->qty,'delete sale');
            }
            $sale->sale_details()->delete();
            $sale->delete();
        }

        if($modal->purchase_id)
        {
            $purchase = Purchase::findOrFail($modal->purchase_id);
            foreach($purchase->purchase_details as $value)
            {
                $this->stock_report($value->item_id, $value->qty, 'delete purchase', $purchase->date);
                $this->stockOut($value->item_id, $value->qty,'delete purchase');
            }
            $purchase->purchase_details()->delete();
            $purchase->delete();
        }

        if($modal->transaction_method == "Journal Entry")
        {
            $journal_entry = JournalEntryDetail::where('transaction_id',$modal->id)->get();
            foreach ($journal_entry as $value)
            {
                $value->delete();
            }
        }

        $data = [];
        foreach ( $modal->transaction_details as $key=>$value) {
            $data[$key]['account_type_id'] = $value->account_type_id;
            $data[$key]['date'] =  $value->date;
        }

        $modal->transaction_details()->delete();
        $modal->delete();

        foreach ( $data as $value) {
            $inputs = [];
            $inputs['account_type_id'] = $value['account_type_id'];
            $inputs['date'] =  $value['date'];
            $this->updateAccountHeadBalanceByDate($inputs);
            $this->updateSharerBalance($inputs);
            $this->updateCashBankBalance($inputs);
        }

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
        $inputs['description'] = " Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
        $this->createDebitAmount($inputs);

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
            $inputs['description'] = "Discount & "." Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
            $this->createDebitAmount($inputs);
        }

        if( isset($inputs['shipping_charge']) && $inputs['shipping_charge']>0)
        {
//                $account_type = AccountType::where('name', 'shipping_cost')->first();
//                $inputs['account_type_id'] = $account_type->id;
//                $inputs['account_name'] = $account->title;
//                $inputs['to_account_name'] = '';
//                $inputs['transaction_id'] = $save_transaction->id;
//                $inputs['amount'] = $inputs['shipping_charge'];
//                $inputs['transaction_type'] = 'dr';
//                $inputs['description'] = "Sale product"." Sale Id : ".$saleInsert->id;
//                $this->createDebitAmount($inputs);

            $account_type = AccountType::where('name', 'cash')->first();
            $inputs['account_type_id'] = $account_type->id;
            $inputs['account_name'] = $account->title;
            $inputs['against_account_type_id'] = $against_account_type->account_type_id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['amount'] = $inputs['shipping_charge'];
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = " Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }

        if(  isset($inputs['unload_cost']) && $inputs['unload_cost'] > 0)
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
            $inputs['description'] = "Labor cost & Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
        }

        if( isset($inputs['transport_cost']) &&  $inputs['transport_cost']>0)
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
            $inputs['description'] = "Transport Cost & Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
            $this->createCreditAmount($inputs);
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
            $inputs['description'] = " Sale Id : ".$saleInsert->id." Sale product: ".$saleLedgerDescription;
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


}