<?php

namespace App\Http\Controllers\Member;

use App\DataTables\CashAndBankAccountDataTable;
use App\DataTables\CashAndBankGLAccountDataTable;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashBankAccountController extends Controller
{
    use TransactionTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( CashAndBankAccountDataTable $dataTable)
    {
        return $dataTable->render('member.cash_or_bank_account.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bank_gl_account( CashAndBankGLAccountDataTable  $dataTable)
    {
        return $dataTable->render('member.cash_or_bank_account.bank_gl_account');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $data['account_types'] = AccountType::active()->get()->pluck('account_code_name','id');
        return view('member.cash_or_bank_account.create', $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'amount_type.required' => "Balance Credited/Debited Required to Select",
            'title.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->getValidationRules(), $customMessages);

        $inputs = $request->all();

        DB::beginTransaction();
        try{

            if($inputs['amount_type'] == "credit") {
                $request['initial_balance'] = $request->initial_balance*(-1);
            }

            $save = CashOrBankAccount::create($inputs);
            $save->description = $request->description;
            $save->internet_banking_url = $request->internet_banking_url;
            $save->initial_balance = $request->initial_balance;
            $save->current_balance = $request->initial_balance;
            $save->account_number = $request->account_number;
            $save->bank_charge_account_id = $request->bank_charge_account_id;
            $save->save();


            $transaction = Transactions::whereHas('transaction_details',
                function ($query) use ($request) {

                    $query->where('account_type_id', $request->account_type_id);
                })->where('transaction_method', 'initial')->authCompany()->first();


            if($transaction)
            {
                $this->transactionRevertAmount($transaction->id);
                $this->transactionDestroy($transaction->id);
            }

            $inputs['cash_or_bank_id'] = $save->id;
            $inputs['supplier_id'] = null;
            $inputs['date'] = db_date_format($request->initial_date);
            $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
            $set_transaction = $this->set_transaction($inputs);

            $account_type = AccountType::find($request->account_type_id);

            if($inputs['amount_type'] == "credit") {
                $against_account_type = AccountType::where('name', 'current_assets')->first();
            }else{
                $against_account_type = AccountType::where('name', 'current_liabilities')->first();
            }

            $inputs['account_name'] = $account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $against_account_type->id;
//            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['against_account_name'] = "Balance B/F";
            $inputs['sharer_name'] = '';
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Initial';
            $inputs['description'] = $inputs['bf_text'];


            if($inputs['amount_type'] == "credit") {
                $inputs['transaction_type'] = 'cr';
                $transactionCr = $this->createCreditAmount($inputs);

//                $inputs['account_type_id'] = $against_account_type->id;
//                $inputs['account_name'] = $against_account_type->display_name;
//                $inputs['to_account_name'] = '';
//                $inputs['date'] = previous_year_last_date();
//                $inputs['transaction_id'] = $set_transaction;
//                $inputs['against_account_type_id'] = $account_type->id;
//                $inputs['against_account_name'] = $account_type->display_name;
//                $inputs['transaction_type'] = 'dr';
//
//                $transactionDr = $this->createDebitAmount($inputs);
            }else{
                $inputs['transaction_type'] = 'dr';
                $transactionDr = $this->createDebitAmount($inputs);

//                $inputs['account_type_id'] = $against_account_type->id;
//                $inputs['account_name'] = $against_account_type->display_name;
//                $inputs['to_account_name'] = '';
//                $inputs['date'] = previous_year_last_date();
//                $inputs['against_account_type_id'] = $account_type->id;
//                $inputs['against_account_name'] = $account_type->display_name;
//                $inputs['transaction_id'] = $set_transaction;
//                $inputs['transaction_type'] = 'cr';
//
//                $transactionCr = $this->createCreditAmount($inputs);
            }

            $status = ['type' => 'success', 'message' => trans('common.cash_and_bank_added_successfully')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_add_cash_and_bank')];
            DB::rollBack();
        }

        DB::commit();

        return back()->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $data['account_types'] = AccountType::active()->get()->pluck('account_code_name','id');
        $model = CashOrBankAccount::find($id);

        if(!$model)
        {
            $status = ['type' => 'danger', 'message' => trans('common.dont_have_any_cash_and_bank')];
            return back()->with('status', $status);
        }

        $transaction = Transactions::whereHas('transaction_details', function ($query) use ($model) {
            $query->where('account_type_id', $model->account_type_id);
        })->where('transaction_method', 'initial')->authCompany()->first();

        $model['date'] = $transaction ? db_date_format($transaction->date) : db_date_format($model->created_at);
        $model['transaction_type'] = $transaction ? ( count($transaction->transaction_details)>0 ? $transaction->transaction_details[0]->transaction_type : "dr") : "dr";
        $model['initial_balance'] = $transaction ? ($transaction->transaction_details ? $transaction->transaction_details[0]->amount : 0) : 0;

        $data['model'] = $model;

        return view('member.cash_or_bank_account.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;
        $customMessages = [
            'amount_type.required' => "Balance Credited/Debited Required to Select",
            'title.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->getValidationRules($id), $customMessages);

        $model = CashOrBankAccount::find($id);
        $inputs = $request->all();

        DB::beginTransaction();
        try{
            $model->update($inputs);

            $model->description = $request->description;
            $model->internet_banking_url = $request->internet_banking_url;
            $model->initial_balance = $request->initial_balance;
//            $model->current_balance = $request->initial_balance;
            $model->account_number = $request->account_number;
            $model->bank_charge_account_id = $request->bank_charge_account_id;
            $model->save();

            $transaction = Transactions::whereHas('transaction_details',
                function ($query) use ($request) {

                    $query->where('account_type_id', $request->account_type_id);
                })->where('transaction_method', 'initial')->authCompany()->get();


            if($transaction)
            {
                foreach($transaction as $trans)
                {
                    $this->transactionRevertAmount($trans->id);
                    $this->transactionDestroy($trans->id);
                }
            }


            $inputs['date'] = db_date_format($inputs['initial_date']);
            $inputs['cash_or_bank_id'] = $model->id;
            $inputs['supplier_id'] = null;
            $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
            $set_transaction = $this->set_transaction($inputs);

            $account_type = AccountType::find($request->account_type_id);

            if($inputs['amount_type'] == "credit") {
                $against_account_type = AccountType::where('name', 'current_assets')->first();
            }else{
                $against_account_type = AccountType::where('name', 'current_liabilities')->first();
            }

            $inputs['account_name'] = $account_type->display_name;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $against_account_type->id;
//            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['against_account_name'] = "Balance B/F";
            $inputs['sharer_name'] = '';
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Initial';
            $inputs['description'] = $inputs['bf_text'];


            if($inputs['amount_type'] == "credit") {
                $inputs['transaction_type'] = 'cr';
                $transactionCr = $this->createCreditAmount($inputs);
            }else{
                $inputs['transaction_type'] = 'dr';
                $transactionDr = $this->createDebitAmount($inputs);
            }

            $status = ['type' => 'success', 'message' => trans('common.cash_and_bank_updated_successfully')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_update_cash_and_bank')];
            DB::rollBack();
        }

        DB::commit();

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = CashOrBankAccount::findOrFail($id);

        $transactions = Transactions::where('cash_or_bank_id', $modal->id)->select('id')->get();

        $transaction_details = TransactionDetail::where('account_type_id', $modal->account_type_id)->whereHas('transaction', function ($query){
            return $query->where('transaction_method', '!=','Initial');
        })->count();

        $notDelatedable = AccountType::where('id','<',46)->orWhereIn('id', [971,929,949,955,956,970])->pluck('id')->toArray();

        if(in_array($modal->account_type_id, $notDelatedable)) {
            return response()->json([
                'data' => [
                    'message' => trans('common.you_cant_delete_this_account_type_please_check_your_set_bank_account_ledger')
                ]
            ], 400);
        }

        if( count($transactions)>1 || $transaction_details > 0)
        {
            return response()->json([
                'data' => [
                    'message' => trans('common.unable_to_delete_because_have_multiple_transaction_from_this_account')
                ]
            ], 400);

        }else{

            $transactions = Transactions::where('cash_or_bank_id', $modal->id)->select('id')->first();
            $this->transactionDestroy($transactions->id);

            $modal->account_type()->delete();
            $modal->delete();

            return response()->json([
                'data' => [
                    'message' => trans('common.successfully_deleted')
                ]
            ], 200);
        }


    }

    private function getValidationRules($id='')
    {
        $rules = [
            'amount_type' => 'required',
            'contact_person' => 'required',
            'phone' => 'required'
        ];

        if($id=='') {
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id';
//            $rules['title'] = 'required|unique:cash_or_bank_accounts,title';
            $rules['account_number'] = 'nullable|unique:cash_or_bank_accounts,account_number';
        }else{
//            $rules['title'] = 'required|unique:cash_or_bank_accounts,title,'.$id;
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id,'.$id;
            $rules['account_number'] = 'nullable|unique:cash_or_bank_accounts,account_number,'.$id;
        }

        return $rules;
    }
}