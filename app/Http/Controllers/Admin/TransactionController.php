<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\AccountHeadDayWiseBalanceTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Company;
use App\Models\TrackAccountHeadBalance;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    use TransactionTrait;

    public function updateDailyCashBalance(Request $request)
    {
        $date = db_date_format($request->date);
        $account_cash = 4;
        $cash = AccountHeadDayWiseBalance::where('account_type_id', $account_cash)->where('date', $date);

        $cash = $this->authCompanyTrait($cash, $request);
        $cash = $cash->first();

        if($cash)
        {
            $cash->balance = create_float_format($request->balance);
            $cash->update();
        }

        $this->adjustmentBalance($account_cash);

        return redirect()->back();
    }

    public function setAccountHeadBalance(){

//        $accountHeads = TransactionDetail::groupBy('account_type_id')->get()->pluck('account_type_id');
//        $deleteAccount = TrackAccountHeadBalance::where('flag', 'delete')->groupBy('account_type_id')->get()->pluck('account_type_id');

        $data = [];
        $data['accountHeads'] = AccountType::select('id','display_name')->get()->pluck('display_name', 'id');

        if(Auth::user()->hasRole(['super-admin', 'developer']))
            $data['companies'] = Company::get()->pluck('company_name', 'id');
        else
            $data['companies'] = Company::where('id',Auth::user()->company_id)->pluck('company_name', 'id');

        $data['processing_month'] = [
            'this_month' => 'This Month',
            'previous_month' => 'Previous Month',
            'last_3_month' => 'Last 3 Months',
            'last_6_month' => 'Last 6 Months' ,
            'last_9_month' => 'Last 9 Months',
            'last_12_month' => 'Last 12 Months',
        ];


        return View('admin.account_heads_ledger.account_head_balance_update', $data);

    }


    public function updateAccountHeadBalance(Request $request){

        $rules = [
            'account_type_id' => 'required',
        ];

        $messages = [
            'account_type_id.required' => 'The Head of accounts is required.',
        ];

        $this->validate($request, $rules, $messages);

        $account_head = $request->account_type_id;
        $processing_month = $request->month_select;

        $account = AccountType::where('id', $account_head)->select('id','display_name')->first();

        $carbon =  new Carbon('first day of this month');

        switch ($processing_month){
            case "this_month":
                $startDate = $carbon;
                break;
            case "previous_month":
                $startDate = new Carbon('first day of last month');
                break;
            case "last_3_month":
                $startDate = $carbon->subMonth(3)->format('Y-m-d');
                break;
            case "last_6_month":
                $startDate = $carbon->subMonth(6)->format('Y-m-d');
                break;
            case "last_9_month":
                $startDate = $carbon->subMonth(9)->format('Y-m-d');
                break;
            case "last_12_month":
                $startDate = $carbon->subMonth(12)->format('Y-m-d');
                break;
            default:
                $date = Transactions::select('date')->orderBy('date','asc')->first();
                $startDate = $date->date;
        }

        $transactions = TransactionDetail::selectRaw('account_type_id, sum(amount) as sum_amount, transaction_type, date')
            ->where('account_type_id', $account_head);

        $transactions = $this->authCompanyTrait($transactions, $request);


        if($processing_month)
        {
            $startDate = db_date_format($startDate);
            $transactions = $transactions->where('date', '>=', $startDate);
        }else{
            $date = Transactions::select('date')->orderBy('date','asc')->first();
            $startDate = $date->date;
            $startDate = db_date_format($startDate);
            $transactions = $transactions->where('date', '>=', $startDate);
        }


        $previousCash = AccountHeadDayWiseBalance::where('account_type_id', $account_head)->where('date', '<', $startDate)->orderBy('date', 'desc');
        $previousCash = $this->authCompanyTrait($previousCash, $request);
        $previousCash = $previousCash->first();

        $transactions = $transactions->groupBy('date')
            ->groupBy('transaction_type')
            ->orderBy('transaction_details.date', 'asc')
            ->get();

            $balance = $previousCash ? $previousCash->balance : 0;
            foreach ($transactions as $value)
            {
                $date = db_date_format($value->date);
                $account_cash = $value->account_type_id;
                $headBalance = AccountHeadDayWiseBalance::where('account_type_id', $account_cash)->where('date', $date)->orderBy('date', 'asc');
                $headBalance = $this->authCompanyTrait($headBalance, $request);
                $headBalance = $headBalance->first();

                if($value->transaction_type=='dr'){
                    $balance += $value->sum_amount;
                }else{
                    $balance -= $value->sum_amount;
                }

                if($headBalance)
                {
                    $headBalance->balance = $balance;
                    $headBalance->update();
                }else{
                    AccountHeadDayWiseBalance::create(
                        [
                            'account_type_id' =>   $account_cash,
                            'date' =>   $date,
                            'balance' =>   $balance,
                            'company_id' =>   $value->company_id,
                        ]
                    );
                }


            }

            if(isset($account_cash))
                $this->adjustmentBalance($account_cash);

        $status = ['type' => 'success', 'message' => $account->display_name.' - Account Head Updated Successfully'];

        return redirect()->back()->with('status', $status);

    }


    public function updateAllAccountHeadBalance(){

        $company = Company::active()->get();

        DB::statement('Delete from transaction_details where transaction_id not in (select id from transactions)');


        foreach ($company as $value2)
        {
            $accountHeads = TransactionDetail::where('company_id', $value2->id)
                ->groupBy('account_type_id')->get()->pluck('account_type_id');

            foreach ( $accountHeads as  $value1)
            {
                $accountTransactionDate = TransactionDetail::where('account_type_id', $value1)->where('company_id', $value2->id)->where('amount', '!=', 0)->groupBy('date')->get()->pluck('date');

                $delete = AccountHeadDayWiseBalance::whereNotIn('date', $accountTransactionDate)->where('account_type_id', $value1)->where('company_id', $value2->id)->delete();

                $transactions = TransactionDetail::selectRaw('account_type_id, sum(amount) as sum_amount, transaction_type, date, company_id')
                    ->where('transaction_details.amount', '>', 0)
                    ->where('account_type_id', $value1)
                    ->where('company_id', $value2->id)
                    ->groupBy('date')
                    ->groupBy('transaction_type')
                    ->orderBy('transaction_details.date', 'asc')
                    ->orderBy('company_id', "asc")
                    ->get();

                $balance = 0;
                foreach ($transactions as $value)
                {
                    $inputs = [];
                    $date =  $inputs['date'] = db_date_format($value->date);
                    $account_cash = $inputs['account_type_id'] = $value->account_type_id;
                    $headBalance = AccountHeadDayWiseBalance::where('account_type_id', $account_cash)
                        ->where('company_id', $value2->id)
                        ->where('date', $date)
                        ->first();

                    if($headBalance)
                    {
                        if($value->transaction_type=='dr'){
                            $balance += $value->sum_amount;
                        }else{
                            $balance -= $value->sum_amount;
                        }

                        $headBalance->balance = create_float_format($balance);
                        $headBalance->update();

                    }else{
                        AccountHeadDayWiseBalance::create(
                            [
                                'account_type_id' =>   $account_cash,
                                'date' =>   $date,
                                'balance' =>   $balance,
                                'company_id' =>   $value2->id,
                            ]
                        );
                    }
                }


                $this->adjustmentBalance($value1);
            }
        }


        Log::channel('daywiseLedgerAccountUpdate')->info(Carbon::now().' :- Account Head Daywise Balance check and update done successfully');

        $status = ['type' => 'success', 'message' =>trans('common.all_account_head_updated_successfully')];

        return redirect()->back()->with('status', $status);

    }

//   if Date transaction Not Found, delete Account Day Wise Balance
    public function deleteAccountDayWiseBalance(Request $request)
    {

        if(Auth::user()->hasRole(['super-admin', 'developer']))
        {
        }else{
            $request['company_id'] = Auth::user()->id;
        }


        $data =  [
            'company_id' => 'required',
        ];
        $messages['company_id.required'] = 'The Company Name is required.';
        $this->validate($request, $data, $messages);

        DB::statement('Delete from transaction_details where transaction_id not in (select id from transactions)');


        if(empty($request->account_type_id))
        {
            $accountHeads = TransactionDetail::where('company_id', $request->company_id)->groupBy('account_type_id')->get()->pluck('account_type_id');

            if(count($accountHeads)>0)
            {
                foreach ( $accountHeads as  $value1) {

                    $accountTransactionDate = TransactionDetail::where('account_type_id', $value1)->where('company_id', $request->company_id)->where('amount', '!=', 0)->groupBy('date')->get()->pluck('date');

                    $delete = AccountHeadDayWiseBalance::whereNotIn('date', $accountTransactionDate)->where('account_type_id', $value1)->where('company_id', $request->company_id)->delete();
                }

                $status = ['type' => 'success', 'message' =>' All Account Day Wise not Date found Deleted Successfully'];
            }else{
                $status = ['type' => 'danger', 'message' =>' All Account Day Wise not Date found Delete not done'];
            }


        }else{

            $account = AccountType::where('id', $request->account_type_id)->select('id','display_name')->first();

            $accountTransactionDate = TransactionDetail::where('account_type_id', $request->account_type_id)->where('company_id', $request->company_id)->where('amount', '!=', 0)->groupBy('date')->get()->pluck('date');

            $delete = AccountHeadDayWiseBalance::whereNotIn('date', $accountTransactionDate)->where('account_type_id', $request->account_type_id)->where('company_id', $request->company_id)->delete();

            $status = ['type' => 'success', 'message' => $account->display_name.' Account  Day Wise not Date found Deleted Successfully'];
        }


        return redirect()->back()->with('status', $status);
    }

    public function transactionNotComplete(){

        $data['transactions'] = DB::table('transaction_details')
            ->leftJoin('transactions', 'transactions.id', '=','transaction_details.transaction_id')
            ->leftJoin('companies', 'companies.id', '=','transaction_details.company_id')
            ->select('transaction_details.*', 'transactions.transaction_code', 'transactions.transaction_method', DB::raw('COUNT(transaction_details.transaction_id) as transaction_count'), 'company_name')
            ->where('transactions.transaction_method', '!=', 'Initial')
            ->groupBy('transaction_id')
            ->orderBy('company_id')
            ->having('transaction_count','<', 2)
            ->get();

        return view('admin.transaction_errors.list', $data);
    }

}