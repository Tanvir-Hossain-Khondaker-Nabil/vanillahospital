<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\TransactionDetailsTrait;
use App\Http\Traits\TransactionHistoryTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\MediaStore;
use App\Models\Reconciliation;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class ReconciliationController extends Controller
{
    use TransactionTrait, TransactionHistoryTrait, TransactionDetailsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reconciliation::authCompany()->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a class="btn btn-xs btn-info" href="'.route('member.reconciliation.show', $row->transaction_code).'"><i class="fa fa-eye"></i></a>
                            <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="'.route( 'member.reconciliation.destroy', $row->id).'">
                                <i class="fa fa-times"></i>
                            </a>';

                    return $btn;
                })
                ->editColumn('date', function ($row){
                    return db_date_month_year_format($row->date);
                })
                ->editColumn('cash_or_bank', function ($row){
                    return $row->cash_or_bank_id ? $row->cash_or_bank_account->title : "";
                })
                ->editColumn('sharer', function ($row){
                    return $row->sharer_id ? $row->sharer->name : "";
                })
                ->editColumn('amount', function ($row){
                    return create_money_format($row->amount);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('member.reconciliations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        if($type == 'supplier')
        {
            $data['type'] = 'Supplier';
            $data['suppliers'] = SupplierOrCustomer::authMember()->active()->get()->pluck('name','id');

        }else{

            $data['type'] = "Bank";
            $data['cash_or_banks'] = CashOrBankAccount::authMember()->withoutSupplierCustomer()->active()->get()->pluck('title','id');
        }

        $data['transaction_categories_id'] = AccountType::findReconciliationAccount()->get()->pluck('account_code', 'id')->toArray();
        $data['transaction_categories'] =  AccountType::findReconciliationAccount()->get()->pluck('display_name', 'id');

        return view('member.reconciliations.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $inputs = $request->all();

        $inputs['ip_address'] = $request->ip();
        $inputs['browser_history'] = $request->header('User-Agent');
        $inputs['flag'] = ($inputs['reconciliation_type'] == 'dr') ? "Deduct" : "Add";

        $total_entry = count($request->account_type_id);
        $account_type_id = $request->account_type_id;
        $amount = $request->amount;
        $description = $request->description;
        $total_amount = 0;

        if(isset($request->cash_or_bank_id))
        {
            $account = CashOrBankAccount::find($request->cash_or_bank_id);

            if($account->account_type_id==null)
            {
                $status = ['type' => 'danger', 'message' => 'Unable to save. There is no AccountType assign in Cash and Bank Account'];
                return back()->with('status', $status);
            }

            $inputs['against_account_type_id'] = $account->account_type_id;
            $inputs['against_account_name'] = $account->account_type->display_name;

        }else{

            $sharer = SupplierOrCustomer::find($request->sharer_id);

            if($sharer->account_type_id==null)
            {
                $status = ['type' => 'danger', 'message' => 'Unable to save. There is no AccountType assign'];
                return back()->with('status', $status);
            }

            $inputs['against_account_type_id'] = $sharer->account_type_id;
            $inputs['against_account_name'] = $sharer->account_type->display_name;
            $inputs['cash_or_bank_id'] = $sharer->cash_or_bank_id;

        }

        $inputs['transaction_code'] = transaction_code_generate();
        $save_transaction = new Transactions();
        $save_transaction->transaction_code = $inputs['transaction_code'];
        $save_transaction->cash_or_bank_id = isset($inputs['cash_or_bank_id']) ? $inputs['cash_or_bank_id'] : null;
        $save_transaction->supplier_id = isset($inputs['sharer_id']) ? $inputs['sharer_id'] : null;
        $save_transaction->date = db_date_format($inputs['date']);
        $save_transaction->amount = array_sum($inputs['amount']);
        $save_transaction->transaction_method = "Reconciliation";
        $save_transaction->save();

        $credit_account_name = $against_account_name = '';

        for($i=0; $i<$total_entry; $i++)
        {
            $reconciliation = [];
            $reconciliation['transaction_code'] = $inputs['transaction_code'];
            $reconciliation['transaction_id'] = $save_transaction->id;
            $reconciliation['cash_or_bank_id'] = isset($inputs['cash_or_bank_id']) ? $inputs['cash_or_bank_id'] : null;
            $reconciliation['sharer_id'] = isset($inputs['sharer_id']) ? $inputs['sharer_id'] : null;
            $reconciliation['date'] = db_date_format($inputs['date']);
            $reconciliation['amount']  = $amount[$i];
            $total_amount += $amount[$i];
            $reconciliation['notes'] = $description[$i];
            $reconciliation['account_type_id'] = $account_type_id[$i];
            Reconciliation::create($reconciliation);

            $account_type = AccountType::find($account_type_id[$i]);

            $inputs['transaction_id'] = $save_transaction->id;
            $inputs['account_name'] = $account_type->display_name;
            $inputs['to_account_name'] = $inputs['against_account_name'];
            $inputs['account_group_id'] = $account_type->parent_id;
            $inputs['amount'] = $amount[$i];
            $inputs['description'] = $description[$i];
            $inputs['account_type_id'] = $account_type_id[$i];
            $inputs['payment_method_id'] = null;

            $credit_account_name = !empty($credit_account_name) ? $credit_account_name.' & ' : '';
            $credit_account_name = $credit_account_name.$account_type->display_name;

            $type = ($inputs['reconciliation_type'] == 'dr') ? 'debit' : 'credit';

            $inputs['transaction_type'] = ($inputs['reconciliation_type'] == 'dr') ? 'cr' : 'dr';
            $inputs['transaction_method'] = 'Reconciliation';

            // Create Transaction Debit Amount
            if($type == 'debit')
                $transactionDe = $this->createDebitAmount($inputs);
            else
                $transactionDe = $this->createCreditAmount($inputs);

            $inputs['transaction_details_id'] = $transactionDe->id;

            // Create Transaction History Per transaction
            $this->historyCreate($inputs, $type);
        }

        $inputs['amount'] = $total_amount;
        $inputs['account_name'] = $inputs['against_account_name'];
        $inputs['account_type_id'] = $inputs['against_account_type_id'];
        $inputs['transaction_type'] = ($inputs['reconciliation_type'] == 'dr') ? 'dr' : 'cr';

        if($type == 'debit')
            $transactionDe = $this->createCreditAmount($inputs);
        else
            $transactionDe = $this->createDebitAmount($inputs);

        $inputs['transaction_details_id'] = $transactionDe->id;

        $this->historyCreate($inputs, $type);

        $status = ['type' => 'success', 'message' => "Reconciliation save Successfully"];
        $data = [];
        $data['transaction_code'] = $inputs['transaction_code'];


        if(!isset($data['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.reconciliations.process', $data)->with('status', $status);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $data['transaction'] = $transaction  = Transactions::where('transaction_code', $code)->get();

        if(count($transaction)<1)
        {
            $status = ['type' => 'danger', 'message' => 'Transaction Code: '.$code." not found. "];
            return back()->with('status', $status);
        }

//         $general_ledger = Transactions::where('transaction_code', $code)
//                                        ->first();

        if($transaction[0]->transaction_method == 'Journal Entry')
            return redirect()->route('member.journal_entry.show', $code);
        elseif($transaction[0]->transaction_method != "Reconciliation")
            return redirect()->route('member.general-ledger.show', $code);


        $condition =  '=';
        $condition_col = "transaction_code";
        $value = $code;

        $general_ledger = $this->transaction_full_details(
            true, true, '', false, false,false, $select_column="",
            false, false, 'ASC', $condition, $condition_col, $value );



        $account_type_bank = AccountType::where('id', $general_ledger[0]->cash_account_type_id)->first();

        $data['general_ledger']['transaction'] = $general_ledger;
        $data['general_ledger']['transaction_code'] = $general_ledger[0]->transaction_code;
        $data['general_ledger']['method'] = $general_ledger[0]->transaction_method;
        $data['general_ledger']['date'] = db_date_month_year_format($general_ledger[0]->date);
        $data['general_ledger']['transaction_form'] = isset($account_type_bank->display_name) ? $account_type_bank->display_name :  '';
        $data['general_ledger']['transaction_form_code'] = isset($account_type_bank->id) ? format_number_digit($account_type_bank->id) : '';
        $data['general_ledger']['entry_by'] = $general_ledger[0]->created_user;


        return view('member.reconciliations.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = Reconciliation::findOrFail($id);

        if($modal->transaction_id)
        {
            $this->transactionRevertAmount($modal->transaction_id);
            $data = Transactions::findOrFail($modal->transaction_id);
            $data->transaction_details()->delete();
            $data->delete();
        }

        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

    protected function validationRules()
    {
        $rules = [
            'amount' => 'required|array|min:1',
            'amount.*' => 'required|string',
            'date' => 'required'
        ];

        return $rules;
    }
}
