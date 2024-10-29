<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\TransactionDetailsTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    use TransactionDetailsTrait, CompanyInfoTrait;
    /**
     * Show list of General Ledger by Auth Company
     */
    public function search(Request $request)
    {
        $inputs = $request->all();

        $multiple_condition = [];

        if( !empty($inputs['date']))
            $multiple_condition['transactions.date'] = db_date_format($request->date);

        if( !empty($inputs['transaction_code']))
            $multiple_condition['transaction_code'] = $request->transaction_code;

        if( !empty($inputs['from_account_type_id']))
            $multiple_condition['transactions.cash_or_bank_id'] = $request->from_account_type_id;

        if( !empty($inputs['to_account_type_id']))
            $multiple_condition['transactions.cash_or_bank_id'] = $request->to_account_type_id;


        $data['modal'] = $this->transaction_full_details(
            $member = true, $company=true, $page = 20, $tr_payment=false, $updated_user=false,
            $tr_category=false, $select_column="tr-data", $group_tr_code=true, $group_tr_type=true,
            $order = 'DESC', '','','', $multiple_condition, $get=true
        );

        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get()->pluck('title','id');

        //  dd('ddd');
        return view('member.general-ledger.index', $data);
    }

    /**
     * Show list of All Ledgers for Only Members
     */
    public function list_ledger(Request $request)
    {
        $inputs = $request->all();

        $multiple_condition = [];


        if( !empty($inputs['date']))
            $multiple_condition['transactions.date'] = db_date_format($request->date);

        if( !empty($inputs['transaction_code']))
            $multiple_condition['transaction_code'] = $request->transaction_code;

        if( !empty($inputs['from_account_type_id']))
            $multiple_condition['transactions.cash_or_bank_id'] = $request->from_account_type_id;

        if( !empty($inputs['to_account_type_id']))
            $multiple_condition['transactions.cash_or_bank_id'] = $request->to_account_type_id;


        $data['modal'] =  $this->transaction_full_details(
            $member = true, $company=true, $page = 20, $tr_payment=false, $updated_user=false,
            $tr_category=false, $select_column="tr-data", $group_tr_code=true, $group_tr_type=true,
            $order = 'DESC', '','','', $multiple_condition, $get=true
        );

        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get()->pluck('title','id');

        return view('member.general-ledger.index', $data);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
      
        $data['transaction'] = $transaction  = Transactions::where('transaction_code', $code)->first();

        if(!$transaction)
        {
            $status = ['type' => 'danger', 'message' => 'Transaction Code: '.$code." not found. "];
            return back()->with('status', $status);
        }

        return view('member.general-ledger.show', $data);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_gl($code)
    {
        $data['transaction'] = $transaction  = Transactions::where('transaction_code', $code)->first();

        if(!$transaction)
        {
            $status = ['type' => 'danger', 'message' => 'Transaction Code: '.$code." not found. "];
            return back()->with('status', $status);
        }

        $data = $this->company($data);
        $data['report_title'] = "General Ledger";

        return view('member.general-ledger.print_gl', $data);

    }


}