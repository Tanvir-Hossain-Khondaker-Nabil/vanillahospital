<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 6:36 PM
 */

namespace App\Http\Traits {


    use App\Models\CashOrBankAccount;
    use App\Models\TransactionHistory;
    use Illuminate\Http\Request;

    trait TransactionHistoryTrait
    {
        public function historyCreate($inputs = array(), $type='')
        {
            $cash_or_bank = CashOrBankAccount::find($inputs['cash_or_bank_id']);
            $inputs['current_balance'] = $cash_or_bank->current_balance;
            $save_transaction_history = new TransactionHistory();

            $save_transaction_history->transaction_details_id = $inputs['transaction_details_id'];
            $save_transaction_history->transaction_code = $inputs['transaction_code'];
            $save_transaction_history->cash_or_bank_id = $inputs['cash_or_bank_id'];
            $save_transaction_history->date = db_date_format($inputs['date']);
            $save_transaction_history->amount = $inputs['amount'];


            if($type == 'debit')
            {
                $save_transaction_history->previous_balance = $inputs['current_balance'];
                $save_transaction_history->current_balance = $inputs['current_balance']+$inputs['amount'];
                $save_transaction_history->transaction_type =  'dr';
            }
            if($type == 'credit')
            {
                $save_transaction_history->previous_balance = $inputs['current_balance'];
                $save_transaction_history->current_balance = $inputs['current_balance']-$inputs['amount'];
                $save_transaction_history->transaction_type =  'cr';
            }


            $save_transaction_history->transaction_method = $inputs['transaction_method'];
            $save_transaction_history->ip_address = $inputs['ip_address'];
            $save_transaction_history->browser_history = $inputs['browser_history'];
            $save_transaction_history->flag = $inputs['flag'];
            $save_transaction_history->save();

            return $save_transaction_history->current_balance;
        }

    }
}
