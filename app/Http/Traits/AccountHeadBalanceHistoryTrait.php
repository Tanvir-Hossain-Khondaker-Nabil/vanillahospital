<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/11/2019
 * Time: 5:22 PM
 */

namespace App\Http\Traits;

use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountHeadsBalanceHistory;
use App\Models\CashOrBankAccount;
use App\Models\TrackAccountHeadBalance;
use Illuminate\Support\Facades\Auth;

trait AccountHeadBalanceHistoryTrait
{

    /*
        Track Account Head Balance
        Create Account Head Balance History
    */

    public function createAccountHeadBalanceHistory($inputs)
    {
        $company_id = Auth::user()->company_id;

        $saveAccountTypeHistory = new AccountHeadsBalanceHistory();
        $saveAccountTypeHistory->account_type_id = $inputs['account_type_id'];
        $saveAccountTypeHistory->account_head_name = $inputs['account_head_name'];
        $saveAccountTypeHistory->transaction_id = $inputs['transaction_id'];
        $saveAccountTypeHistory->balance = $inputs['amount'];
        $saveAccountTypeHistory->date =  db_date_format($inputs['date']);
        $saveAccountTypeHistory->member_id = Auth::user()->member_id;
        $saveAccountTypeHistory->created_by = Auth::user()->id;
        $saveAccountTypeHistory->company_id = $company_id;
        $saveAccountTypeHistory->save();


        $last_date_balance = TrackAccountHeadBalance::where('account_type_id', $inputs['account_type_id'])
                                    ->where('company_id', $company_id)->orderBy('id', 'DESC')->first();

        $last_date_balance = $last_date_balance ? $last_date_balance->current_balance : 0;


        $trackAccountHeadBalance = new TrackAccountHeadBalance();
        $trackAccountHeadBalance->account_type_id = $inputs['account_type_id'];
        $trackAccountHeadBalance->transaction_id = $inputs['transaction_id'];
        $trackAccountHeadBalance->previous_balance = $last_date_balance;
        $trackAccountHeadBalance->current_balance = $inputs['transaction_type'] == "dr" ? $last_date_balance+$inputs['amount'] : $last_date_balance-$inputs['amount'];
        $trackAccountHeadBalance->amount = $inputs['amount'];
        $trackAccountHeadBalance->flag = (isset($inputs['flag']) && $inputs['flag'] == "delete") ? "delete" : "add";
        $trackAccountHeadBalance->date =  db_date_format($inputs['date']);
        $trackAccountHeadBalance->company_id = $company_id;
        $trackAccountHeadBalance->save();

        if( $saveAccountTypeHistory->id > 0 && $trackAccountHeadBalance->id > 0)
            return $trackAccountHeadBalance->current_balance;
        else
            return 0;

    }
}
