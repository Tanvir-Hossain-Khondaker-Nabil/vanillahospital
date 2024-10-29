<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/11/2019
 * Time: 5:22 PM
 */

namespace App\Http\Traits;


use App\Models\AccountHeadDayWiseBalance;
use App\Models\CashOrBankAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;

trait AccountHeadDayWiseBalanceTrait
{
    public function createAccountHeadDayWiseBalanceHistory($inputs)
    {
        $company_id = Auth::user()->company_id;
        $saveAccountHeadDayWiseBalance = new AccountHeadDayWiseBalance();

        $accountHeadDayWiseBalance = $saveAccountHeadDayWiseBalance->where('account_type_id', $inputs['account_type_id'])
            ->where('company_id', $company_id)->where('date', db_date_format($inputs['date']))->first();


        if($accountHeadDayWiseBalance)
        {
            $accountHeadDayWiseBalance->balance = $inputs['transaction_type'] == "dr" ? $accountHeadDayWiseBalance->balance+$inputs['amount'] : $accountHeadDayWiseBalance->balance-$inputs['amount'];
//            $accountHeadDayWiseBalance->balance = $inputs['amount'];

            $accountHeadDayWiseBalance->update();

        }else{
            $accountHeadDayWiseBalance = $saveAccountHeadDayWiseBalance->where('account_type_id', $inputs['account_type_id'])
                ->where('company_id', $company_id)
                ->orderBy('date','desc')->first();

            $saveAccountHeadDayWiseBalance->account_type_id = $inputs['account_type_id'];
            $saveAccountHeadDayWiseBalance->date =  db_date_format($inputs['date']);
            $saveAccountHeadDayWiseBalance->company_id = $company_id;
            $saveAccountHeadDayWiseBalance->balance = $accountHeadDayWiseBalance ? $inputs['transaction_type'] == "dr" ? $accountHeadDayWiseBalance->balance+$inputs['amount'] : $accountHeadDayWiseBalance->balance-$inputs['amount'] : $inputs['amount'];
//            $saveAccountHeadDayWiseBalance->balance = $inputs['amount'];
            $saveAccountHeadDayWiseBalance->save();

        }


        return 1;
    }


    public function updateAccountHeadBalanceByDate($data){

        $account_head = $data['account_type_id'];
        $startDate = db_date_format($data['date']);

        $previousCash = AccountHeadDayWiseBalance::where('account_type_id', $account_head)->where('date', '<', $startDate);

        $transactions = TransactionDetail::selectRaw('account_type_id, sum(amount) as sum_amount, transaction_type, date')
            ->where('account_type_id', $account_head)
            ->where('date', '>=', $startDate);

        if(isset(Auth::user()->company_id) && Auth::user()->company_id != null)
        {
            $company_id  =  Auth::user()->company_id;
            $transactions = $transactions->where('company_id', $company_id);
            $previousCash = $previousCash->where('company_id', $company_id);

            $transactions =  $transactions->groupBy('date')->groupBy('transaction_type')->orderBy('date')->get();

//            dd($transactions);

            $previousCash = $previousCash->orderBy('date', 'desc')->first();

            $balance = $previousCash ? $previousCash->balance : 0;

            foreach ($transactions as $value)
            {
                $date = $value->date;
                $account_cash = $value->account_type_id;
                $headBalance = AccountHeadDayWiseBalance::where('account_type_id', $account_cash)->where('date', $date)->authCompany()->first();

                if($value->transaction_type=='dr'){
                    $balance += $value->sum_amount;
                }else{
                    $balance -= $value->sum_amount;
                }

                if($headBalance)
                {
                    AccountHeadDayWiseBalance::where('account_type_id', $account_cash)->where('date', $date)->authCompany()->update(['balance' => $balance]);
                }else{
                    AccountHeadDayWiseBalance::create(
                        [
                            'account_type_id' =>   $account_cash,
                            'date' =>   $date,
                            'balance' =>   $balance,
                            'company_id' =>   Auth::user()->company_id,
                        ]
                    );
                }
            }
        }


    }


    private function authCompanyTrait($query, $request, $table="")
    {
        if(!empty($table))
        {
            $table = $table.".".'company_id';
        }else{
            $table = 'company_id';
        }

        if(isset(Auth::user()->company_id) && Auth::user()->company_id != null)
        {
            $company_id  =  Auth::user()->company_id;
            $query = $query->where($table, $company_id);
        } elseif (isset($request->company_id)) {
            $company_id = $request->company_id;
            $query = $query->where($table, $company_id);
        }

        return $query;
    }


    public function account_head_balance_check($value, $request, $date)
    {
        $accountBalance = AccountHeadDayWiseBalance::where('account_type_id', $value)
            ->where('balance', '!=', 0);
        $accountBalance = $this->authCompanyTrait($accountBalance, $request);
        $accountBalance = $accountBalance->where('date', '<=', $date)->orderby('date', 'desc')->first();

        return $accountBalance;
    }
}
