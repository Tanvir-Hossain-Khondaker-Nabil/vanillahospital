<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AccountTypesDataTable;
use App\Http\Traits\AccountTypeTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\SupplierOrCustomer;
use App\Models\CashOrBankAccount;
use App\Models\GLAccountClass;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountTypeController extends Controller
{

    use TransactionTrait, AccountTypeTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AccountTypesDataTable $dataTable)
    {
        
        return $dataTable->render('admin.account_types.index');
    }


    public function group()
    {
        $data['account_types'] = AccountType::group()->paginate($this->perPage);
        return view('admin.account_types.group', $data);
    }

    public function heads()
    {
        $data['account_types'] = $this->select_heads($this->perPage);

        $data['title'] = 'Heads';
        return view('admin.account_types.heads', $data);
    }

    public function sub_heads()
    {
        $data['account_types'] = $this->select_sub_heads($this->perPage);

        $data['title'] = 'Sub-Heads';
        return view('admin.account_types.heads', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['accounts'] = AccountType::onlyAccount()->active()->get()->pluck('display_name','id');
        $data['account_groups'] = GLAccountClass::active()->get()->pluck('name','id');

        return view( 'admin.account_types.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['name'] = snake_case($request->name);
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'amount_type.required' => "Balance Credited/Debited Required to Select",
            'name.unique_with' => 'The :attribute is already exist for this company',
            'display_name.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->rules(), $customMessages);

        $inputs = $request->all();

        DB::beginTransaction();
        try{
            $save = AccountType::create($request->all());

            $inputs['cash_or_bank_id'] = null;
            $inputs['supplier_id'] = null;
            $inputs['date'] = db_date_format($request->initial_date);
            $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
            $set_transaction = $this->set_transaction($inputs);

            $account_type = new AccountType();

            if($inputs['amount_type'] == "credit") {
                $against_account_type = $account_type->where('name', 'current_assets')->first();
            }else{
                $against_account_type = $account_type->where('name', 'current_liabilities')->first();
            }

            $inputs['account_name'] = $save->display_name;
            $inputs['account_type_id'] = $save->id;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $against_account_type->id;
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

            $status = ['type' => 'success', 'message' => trans('common.account_type_added_successfully')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_add_account_type')];
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
        $modal = AccountType::find($id);

        if(!$modal)
        {
            $status = ['type' => 'danger', 'message' => trans('common.unable_to_find_data')];
            return back()->with('status', $status);
        }

        $data['accounts'] = AccountType::onlyAccount()->active()->get()->pluck('display_name','id');
        $data['account_groups'] = GLAccountClass::active()->get()->pluck('name','id');

        $transaction = Transactions::whereHas('transaction_details', function ($query) use ($modal) {
            $query->where('account_type_id', $modal->id);
        })->where('transaction_method', 'initial')->first();

        $modal['date'] = $transaction ? db_date_format($transaction->date) : db_date_format($modal->created_at);
        $modal['transaction_type'] = $transaction ? ($transaction->transaction_details ? $transaction->transaction_details[0]->transaction_type : "dr") : "dr";
        $modal['initial_balance'] = $transaction ? ($transaction->transaction_details ? $transaction->transaction_details[0]->amount : 0) : 0;

        $data['modal'] = $modal;

        return view( 'admin.account_types.edit', $data);
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
        $modal = AccountType::find($id);
        if(!$modal)
        {
            $status = ['type' => 'danger', 'message' => trans('common.unable_to_find_data')];
            return back()->with('status', $status);
        }

        $request['name'] = snake_case($request->name);
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'amount_type.required' => "Balance Credited/Debited Required to Select",
            'name.unique_with' => 'The :attribute is already exist for this company',
            'display_name.unique_with' => 'The :attribute is already exist for this company'
        ];

        $this->validate($request, $this->rules($id), $customMessages);

        $inputs = $request->all();
        DB::beginTransaction();
        try{

            $save = $modal->update($inputs);

            $transaction = Transactions::whereHas('transaction_details', function ($query) use ($modal) {
                $query->where('account_type_id', $modal->id);
            })->where('transaction_method', 'initial')->first();

            if($transaction)
            {
                $inputs['date'] = db_date_format($transaction->date);
                $this->transactionRevertAmount($transaction->id);
                $this->transactionDestroy($transaction->id);
            }


            $inputs['date'] = db_date_format($inputs['initial_date']);
            $inputs['cash_or_bank_id'] = null;
            $inputs['supplier_id'] = null;
            $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
            $set_transaction = $this->set_transaction($inputs);

            $account_type = new AccountType();

            if($inputs['amount_type'] == "credit") {
                $against_account_type = $account_type->where('name', 'current_assets')->first();
            }else{
                $against_account_type = $account_type->where('name', 'current_liabilities')->first();
            }

            $inputs['account_name'] = $modal->display_name;
            $inputs['to_account_name'] = '';
            $inputs['account_type_id'] = $modal->id;
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

            $status = ['type' => 'success', 'message' => trans('common.account_type_update_successfully')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_update_account_type')];
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
        $notDelatedable = AccountType::where('id','<',46)->orWhereIn('id', [971,929,949,955,956,970])->pluck('id')->toArray();

        if(in_array($id, $notDelatedable)) {
            return response()->json([
                'data' => [
                    'message' => trans('common.you_cant_delete_this_account_type')
                ]
            ], 400);
        }


        $transaction_details = TransactionDetail::where('account_type_id', $id)->whereHas('transaction', function ($query){
            return $query->where('transaction_method', '!=','Initial');
        })->count();

        if( $transaction_details > 0)
        {
            return response()->json([
                'data' => [
                    'message' => trans('common.unable_to_delete_because_have_multiple_transaction_from_this_account')
                ]
            ], 400);
        }

        $transactions = TransactionDetail::where('account_type_id', $id)->first();

        if($transactions)
        {
            $this->transactionDestroy($transactions->transaction_id);
        }

        $modal = AccountType::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);

    }

    public function forcedelete($id)
    {
        $notDelatedable = AccountType::where('id','<',46)->orWhereIn('id', [971,929,949,955,956,970])->pluck('id')->toArray();

        if(in_array($id, $notDelatedable)) {
            return response()->json([
                'data' => [
                    'message' => trans('common.you_cant_delete_this_account_type')
                ]
            ], 400);
        }


        $transaction_details = TransactionDetail::where('account_type_id', $id)->whereHas('transaction', function ($query){
            return $query->where('transaction_method', '!=','Initial');
        })->count();

        if( $transaction_details > 0)
        {
            return response()->json([
                'data' => [
                    'message' => trans('common.unable_to_delete_because_have_multiple_transaction_from_this_account')
                ]
            ], 400);
        }

        $transactions = TransactionDetail::where('account_type_id', $id)->first();

        if($transactions)
        {
            $this->transactionDestroy($transactions->transaction_id);
        }

        $sharer = SupplierOrCustomer::where('account_type_id', $id)->withTrashed()->first();
        $sharer ? $sharer->forceDelete() : '';

        $cashBank = CashOrBankAccount::where('account_type_id', $id)->withTrashed()->first();
        $cashBank ? $cashBank->forceDelete() : '';

        $modal = AccountType::withTrashed()->findOrFail($id);
        $modal->forceDelete();

        return response()->json([
            'data' => [
                'message' => trans('common.successfully_deleted')
            ]
        ], 200);
    }

    private function rules($id='')
    {
        if(is_null($id))
        {
            $data =  [
                'name' => 'required|unique_with:account_types,name, company_id, member_id',
                'display_name' => 'required|unique_with:account_types,display_name, name, company_id, member_id',
            ];
        }else{
            $data =  [
                'name' => 'required|unique_with:account_types,name, company_id, member_id,'.$id,
                'display_name' => 'required|unique_with:account_types,display_name, name, company_id, member_id,'.$id,
            ];
        }

        $data['amount_type'] = "required";

        return $data;
    }
}