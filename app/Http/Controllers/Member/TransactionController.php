<?php

namespace App\Http\Controllers\Member;

use App\DataTables\TransactionDataTable;
use App\DataTables\TransactionTransferDataTable;
use App\Http\Services\WarehouseStock;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\PaymentDetailTrait;
use App\Http\Traits\StockTrait;
use App\Http\Traits\TransactionHistoryTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\JournalEntryDetail;
use App\Models\MediaStore;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionCategory;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Barryvdh\Snappy;

class TransactionController extends Controller
{
    use TransactionTrait, TransactionHistoryTrait, FileUploadTrait, PaymentDetailTrait, StockTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( TransactionDataTable $dataTable)
    {
        return $dataTable->render('member.transaction.index');
    }

    public function manage_daily_sheet()
    {
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $data['type'] = "Manage Daily Sheet";
        $data['transaction_categories_id'] = AccountType::authCompany()->get()->pluck('account_code', 'id')->toArray();
        $data['transaction_categories'] =  AccountType::authCompany()->get()->pluck('display_name', 'id');
        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();
        $data['sharers'] = SupplierOrCustomer::authMember()->authCompany()->active()->get()->pluck('name','id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name','id');
        return view('member.transaction.manage_daily_sheet', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {


        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $data = [];
        $transaction_accounts = CashOrBankAccount::authMember()->authCompany()->active()->get()->pluck('account_type_id')->toArray();

        if( $type == 'Expense')
        {
            $data['type'] = 'Expense';
            $data['transaction_categories_id'] = AccountType::whereNotIn('id', $transaction_accounts)->authCompany()->get()->pluck('account_code', 'id')->toArray();
            $data['transaction_categories'] =  AccountType::whereNotIn('id', $transaction_accounts)->authCompany()->get()->pluck('display_name', 'id');
        }else{
            $data['type'] = $type;
//            $data['transaction_categories_id'] = AccountType::whereIn('id', $transaction_accounts)->get()->pluck('account_code', 'id')->toArray();
//            $data['transaction_categories'] =  AccountType::whereIn('id', $transaction_accounts)->get()->pluck('display_name', 'id');
//
            $data['transaction_categories_id'] = AccountType::authCompany()->get()->pluck('account_code', 'id')->toArray();
            $data['transaction_categories'] =  AccountType::authCompany()->get()->pluck('display_name', 'id');
        }
        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();
        $data['sharers'] = SupplierOrCustomer::authMember()->authCompany()->active()->get()->pluck('name_address','id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name','id');

        if($type == "Income")
            return view('member.transaction.income', $data);
        else
            return view('member.transaction.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {

        $this->validate($request, $this->validationRules());

        $inputs = $request->all();

        $total_entry = count($request->account_type_id);

        $account = CashOrBankAccount::find($request->cash_or_bank_id);

        if($account->account_type_id==null)
        {
            $status = ['type' => 'danger', 'message' => 'Unable to save. There is no AccountType assign in Cash and Bank Account'];
            return back()->with('status', $status);
        }

        $inputs['account_name'] = $account->title;
        $inputs['current_balance'] = empty($account->current_balance) ? 0.00 : $account->current_balance;

        if(isset($request->supplier_id)) {
            $sharer = SupplierOrCustomer::find($request->supplier_id);
            $inputs['sharer_name'] = $sharer->name;

        }else{
            $inputs['sharer_name'] = '';
        }

        $status['message'] = '';

        DB::beginTransaction();
        try{

            $inputs['transaction_code'] = transaction_code_generate();

            $save_transaction = new Transactions();
            $save_transaction->transaction_code = $inputs['transaction_code'];
            $save_transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
            $save_transaction->supplier_id = $inputs['supplier_id'];
            $save_transaction->date = db_date_format($inputs['date']);
            $save_transaction->amount = array_sum($inputs['amount']);
            $save_transaction->notation = $inputs['notation'];
            $save_transaction->transaction_method = $inputs['transaction_method'];
            $save_transaction->save();

            $inputs['ip_address'] = $request->ip();
            $inputs['browser_history'] = $request->header('User-Agent');
            $inputs['flag'] = "add";

            $account_type_id = $request->account_type_id;
            $payment_method_id = $request->payment_method;


            $amount = $request->amount;
            $description = $request->description;

            $total_amount = 0;
            $credit_account_name = '';

            $inputs['transaction_id'] = $save_transaction->id;

            $cash_bank = CashOrBankAccount::find($inputs['cash_or_bank_id']);
            $againstAccount = SupplierOrCustomer::find($inputs['supplier_id']);

            $inputs['against_account_type_id'] = $againstAccount ? $againstAccount->account_type_id : $cash_bank->account_type_id;
            $inputs['against_account_name'] = $againstAccount ? $againstAccount->account_type->display_name : $cash_bank->account_type->display_name;

            $against_account_name = '';

            for($i=0; $i<$total_entry; $i++)
            {
                $account_type = AccountType::find($account_type_id[$i]);
                if(!$account_type)
                {
                    $status['message'] = 'Account Name not found';
                }

                $credit_account_name = !empty($credit_account_name) ? $credit_account_name.' & ' : ' ';
                $credit_account_name = $credit_account_name.$account_type->display_name;

                $against_account_name = !empty($against_account_name) ? $against_account_name." & ".$account_type->display_name : $account_type->display_name;
                $inputs['to_account_name'] = $account_type->display_name;
                $inputs['account_group_id'] = $account_type->parent_id;
                $inputs['amount'] = $amount[$i];
                $inputs['description'] = $description[$i];
                $inputs['account_type_id'] = $account_type_id[$i];
                $inputs['payment_method_id'] = $payment_method_id[$i];

                $type = ($inputs['transaction_method'] == 'Expense') ? 'debit' : 'credit';
                // Create Transaction Debit Amount
                if($type == 'debit')
                    $transactionDr = $this->createCreditAmount($inputs);
                else
                    $transactionDr = $this->createCreditAmount($inputs);

                $inputs['transaction_details_id'] = $transactionDr->id;

                // Add Payment Details using payment Method based
                /*
                 *  TODO: This will be use in Later for Re-Conciliation
                 */
//                $this->checkingPaymentMethod($inputs, $i);

                $total_amount = $total_amount+$amount[$i];

                // Create Transaction History Per transaction
                $last_balance = $this->historyCreate($inputs, $type);
                $inputs['current_balance'] = $last_balance;

            }

            // Update Transaction Total Amount Balance
            $save_transaction->amount = $total_amount;
            $save_transaction->save();

            // Update Cash and Bank Account Balance
//            $this->bankAccountBalanceUpdate($inputs['transaction_method'], $account, $total_amount);

            if( isset($request->supplier_id)&& $sharer->customer_type=='supplier' )
                $inputs['current_balance'] = empty($sharer->supplier_current_balance) ? 0.00 : $sharer->supplier_current_balance;
            else
                $inputs['current_balance'] = empty($sharer->customer_current_balance) ? 0.00 : $sharer->customer_current_balance;


            $inputs['against_account_type_id'] = $inputs['account_type_id'];
            $inputs['amount'] = $total_amount;
            $inputs['account_type_id'] = $account->account_type_id;
            $inputs['against_account_name'] = $against_account_name;
            $inputs['account_group_id'] = $account->account_type->account_type_id;

            $inputs['to_account_name'] = $credit_account_name;

            // Create Transaction Credit Amount
            if($type == 'debit')
                $transactionCr = $this->createDebitAmount($inputs);
            else
                $transactionCr = $this->createDebitAmount($inputs);

            $inputs['transaction_details_id'] = $transactionCr->id;
            $type = ($inputs['transaction_method'] == 'Expense') ? 'debit' : 'credit';

            // Create Transaction History Per transaction
            $this->historyCreate($inputs, $type);

            if(isset($request->supplier_id)) {
                $this->sharerBalanceUpdate($inputs['transaction_method'], $sharer, $total_amount);
            }

            $text = $status['message']."<br/>".ucfirst($request->transaction_method).trans('common.save_successfully');
            $status = ['type' => 'success', 'message' => $text];

            $status['transaction_code'] = $inputs['transaction_code'];


            if($request->hasFile('attach'))
            {
                $file = $request->file('attach');

                $mediaData = $this->fileUploadWithDetails($file, $inputs['transaction_code'], null);
                $mediaData['model_id'] = $save_transaction->id;
                $mediaData['use_model'] = "Transactions";
                if($mediaData['file_name'] == null)
                {
                    $status = ['type' => 'success', 'message' => $status['message']." ".trans('common.unable_to_save_file')];

                }else{

                    MediaStore::create($mediaData);

                }
            }

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_save')." ".ucfirst($request->transaction_method)];
            DB::rollBack();
        }

        DB::commit();


        if(!isset($status['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.transaction.store-transaction')->with('status', $status);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function incomeStore(Request $request)
    {
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $inputs = $request->all();

        $total_entry = count($request->account_type_id);

        $status['message'] = '';

        DB::beginTransaction();
        try{

        $inputs['transaction_code'] = transaction_code_generate();
        $inputs['date'] = db_date_format($inputs['date']);
        $save_transaction = new Transactions();
        $save_transaction->transaction_code = $inputs['transaction_code'];
        $save_transaction->date = $inputs['date'];
        $save_transaction->amount = 0.0;
        $save_transaction->notation = $inputs['description'] = $inputs['notation'];
        $save_transaction->transaction_method = $inputs['transaction_method'] = "Income";
        $save_transaction->save();


        $inputs['ip_address'] = $request->ip();
        $inputs['browser_history'] = $request->header('User-Agent');
        $inputs['flag'] = "add";

        $account_type_id = $request->account_type_id;
        $income_from = $request->income_from;
        $payment_method_id = $request->payment_method;
        $amount = $request->amount;
        $description = $request->description;

        $dr_amount = 0;
        $cr_amount = 0;
        $credit_account_name = '';

        $inputs['transaction_id'] = $save_transaction->id;
        $type = $inputs['transaction_method'];

        $accountFrom = AccountType::find($income_from);
        $acc = $accAgainst = '';


        for($i=0; $i<$total_entry; $i++)
        {
            $account_type = AccountType::find($account_type_id[$i]);
            $inputs['account_group_id'] = $account_type->parent_id;
            if(!$account_type)
            {
                $status['message'] = 'Account Name not found';
            }
            $accAgainst .= ($accAgainst ? " & " : " ").$account_type->display_name;

            $inputs['amount'] = $amount[$i];
            $inputs['description'] .= $description[$i];
            $inputs['short_description'] = $description[$i];
            $inputs['account_type_id'] = $account_type_id[$i];
            $inputs['payment_method_id'] = $payment_method_id[$i];
            $inputs['cash_or_bank_id'] = '';
            $inputs['transaction_type']='dr';
            $inputs['account_name'] = $account_type->display_name;
            $inputs['to_account_name'] = $accountFrom->display_name;
            $inputs['against_account_name'] = $accountFrom->display_name;

            // Create Transaction Credit Amount
            $transactionCr = $this->createCreditAmount($inputs);
            $inputs['transaction_details_id'] = $transactionCr->id;

            $cr_amount = $cr_amount+$amount[$i];


            // Add Payment Details using payment Method based
            /*
             *  TODO: This will be use in Later for Re-Conciliation
             */
//                $this->checkingPaymentMethod($inputs, $i);

        }

        $inputs['transaction_type']='cr';
        $inputs['account_type_id'] = $accountFrom->id;
        $inputs['account_name'] = $accountFrom->display_name;
        $inputs['to_account_name'] = '';
        $inputs['against_account_name'] = $accAgainst;
        $inputs['amount'] = $cr_amount;

        // Create Transaction Debit Amount
        $transactionDr = $this->createDebitAmount($inputs);
        $inputs['transaction_details_id'] = $transactionDr->id;

        $save_transaction->amount = $cr_amount;
        $save_transaction->save();

        $status = ['type' => 'success', 'message' => $status['message']."<br/>".ucfirst($request->transaction_method).trans('common.save_successfully')];

        $status['transaction_code'] = $inputs['transaction_code'];


        if($request->hasFile('attach'))
        {
            $file = $request->file('attach');

            $mediaData = $this->fileUploadWithDetails($file, $inputs['transaction_code'], null);
            $mediaData['model_id'] = $save_transaction->id;
            $mediaData['use_model'] = "Transactions";
            if($mediaData['file_name'] == null)
            {
                $status = ['type' => 'success', 'message' => $status['message']." ".trans('common.unable_to_save_file')];

            }else{

                MediaStore::create($mediaData);

            }
        }

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_save')." ".ucfirst($request->transaction_method)];
            DB::rollBack();
        }

        DB::commit();


        if(!isset($status['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.transaction.store-transaction')->with('status', $status);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listTransfer( TransactionTransferDataTable $dataTable)
    {
        return $dataTable->render('member.transaction.transfer-list');
    }


    /**
     * Show the form for creating a transfer.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer()
    {
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $data = [];
        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get()->pluck('title','id');
        $data['payment_methods'] = PaymentMethod::active()->authCompany()->get()->pluck('name','id');

        return view('member.transaction.transfer', $data);
    }

    public function saveTransfer(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'from_cash_or_bank_id' => 'required',
            'to_cash_or_bank_id' => 'required',
            'date' => 'required'
        ];

        $this->validate($request, $rules);


        $from_account = CashOrBankAccount::find($request->from_cash_or_bank_id);
        $to_account = CashOrBankAccount::find($request->to_cash_or_bank_id);


        if( empty($from_account->account_type_id) || empty($to_account->account_type_id)){
            $status = ['type' => 'danger', 'message' => 'There is no Account Type for Transaction from or To. Please add Those'];
            return back()->with('status', $status);
        }

        DB::beginTransaction();
        try{

            $inputs = $request->all();
            $inputs['transaction_code'] = transaction_code_generate();
            $inputs['amount']  = $request->amount;

            $inputs['date'] = db_date_format($request->date);
            $inputs['account_name'] = $from_account->title;
            $inputs['current_balance'] = empty($from_account->current_balance) ? 0.00 : $from_account->current_balance;
            $inputs['to_account_name'] = $to_account->title;
            $inputs['transaction_method'] = "Transfer";
            $inputs['notation'] = $inputs['description'];
            $inputs['cash_or_bank_id'] = $request->from_cash_or_bank_id;
            $transaction =  Transactions::create($inputs);

            $inputs['transaction_id'] = $transaction->id;
            $inputs['account_type_id'] = $from_account->account_type_id;
            $inputs['against_account_type_id'] = $to_account->account_type_id;
            $inputs['against_account_name'] = $to_account->account_type->display_name;
            $inputs['account_group_id'] = $from_account->account_type->account_type_id;
            $inputs['payment_method_id'] = $request->payment_method_id;


            $inputs['ip_address'] = $request->ip();
            $inputs['browser_history'] = $request->header('User-Agent');
            $inputs['flag'] = 'Add';
            $inputs['transaction_type'] = 'cr';
            $inputs['description'] = (isset($inputs['description']) ? $inputs['description']."---- && ----":'')."Amount Transfer ".$from_account->account_type->display_name." to ".$to_account->account_type->display_name;
            $transactionCr = $this->createCreditAmount($inputs);
            $inputs['transaction_details_id'] = $transactionCr->id;
            $this->historyCreate($inputs, 'Credit');

            $inputs['cash_or_bank_id'] = $request->to_cash_or_bank_id;
            $inputs['account_type_id'] = $to_account->account_type_id;
            $inputs['against_account_type_id'] = $from_account->account_type_id;
            $inputs['against_account_name'] = $from_account->account_type->display_name;
            $inputs['account_group_id'] = $from_account->account_type->account_type_id;
            $inputs['transaction_type'] = 'dr';

            $transactionDr = $this->createDebitAmount($inputs);
            $inputs['transaction_details_id'] = $transactionDr->id;
            $this->historyCreate($inputs, 'Debit');

            /*
                 *  TODO: This will be use in Later for Re-Conciliation
                 */
//                $this->checkingPaymentMethod($inputs, $i);


            $status = ['type' => 'success', 'message' => trans('common.save_successfully')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_save')];
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
        return view('member.transaction.store-transaction');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $data = [];
        $transaction = $data['transactions'] = Transactions::where('transaction_code', $code)->with(['transaction_details' => function ($query)  {
        return $query->orderBy('id', "asc");
    }])->first();

        if(!$transaction)
            abort(404);

        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;


        if(in_array($transaction->transaction_method, ["Income", "Deposit", "Expense", "Payment"]))
        {
            $transaction_form = $transaction->transaction_details()->latest('id')->first();
            $data['transaction_form'] = $transaction_form->account_type_id;

//            dd($transaction_form);
            $data['type'] = $transaction->transaction_method;
            $data['transaction_categories_id'] = AccountType::authCompany()->get()->pluck('account_code', 'id')->toArray();
            $data['transaction_categories'] =  AccountType::authCompany()->get()->pluck('display_name', 'id');

            $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();
            $data['sharers'] = SupplierOrCustomer::authMember()->authCompany()->active()->get()->pluck('name_address','id');
            $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name','id');

            return view('member.transaction.edit', $data);

        }elseif($transaction->transaction_method == "Purchases"){

            return redirect()->route('member.purchase.edit', $transaction->purchase_id);

        }elseif($transaction->transaction_method == "Sales"){

            return redirect()->route('member.sales.edit', $transaction->sale_id);

        }elseif($transaction->transaction_method == "Journal Entry"){

            return redirect()->route('member.journal_entry.edit', $transaction->id);

        }else{
            abort(404);
        }

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
        $inputs = $request->all();
        $transaction = Transactions::findOrFail($id);

        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $transaction_form = $request->transaction_form;
        $cash_bank = CashOrBankAccount::where('account_type_id', $transaction_form)->first();

        if($cash_bank)
            $inputs['cash_or_bank_id'] = $cash_bank->id;
        else
            $inputs['cash_or_bank_id'] = null;


        if(isset($request->supplier_id)) {
            $sharer = SupplierOrCustomer::find($request->supplier_id);
            $inputs['sharer_name'] = $sharer->name;

        }else{
            $inputs['sharer_name'] = '';
        }


        $total_entry = count($request->account_type_id);

        $status['message'] = '';
        DB::beginTransaction();
        try{

        $inputs['date'] = db_date_format($inputs['date']);
        $transaction->date = $inputs['date'];
        $transaction->cash_or_bank_id = $inputs['cash_or_bank_id'];
        $transaction->supplier_id = $inputs['supplier_id'];
        $transaction->amount = 0.0;
        $transaction->notation = $inputs['description'] = $inputs['notation'];
        $transaction->transaction_method = $inputs['transaction_method'] ;
        $transaction->save();

        $trans_details = TransactionDetail::where('transaction_id', $transaction->id)->get();
        $this->transactionRevertAmount($transaction->id);

        foreach ($trans_details as $key => $value)
        {
            $data = [];
            $data[$key]['account_type_id'] = $value->account_type_id;
            $data[$key]['date'] =  $value->date;

            $value->delete();
        }

        foreach ( $data as $value2) {
            $input = [];
            $input['account_type_id'] = $value2['account_type_id'];
            $input['date'] =  $value2['date'];
            $this->updateAccountHeadBalanceByDate($input);
        }

        $inputs['ip_address'] = $request->ip();
        $inputs['browser_history'] = $request->header('User-Agent');
        $inputs['flag'] = "add";

        $account_type_id = $request->account_type_id;
        $payment_method_id = $request->payment_method;
        $amount = $request->amount;
        $description = $request->description;

        $dr_amount = 0;
        $cr_amount = 0;
        $credit_account_name = '';

        $inputs['transaction_id'] = $transaction->id;
        $type = $inputs['transaction_method'];

        $accountFrom = AccountType::find($transaction_form);
        $acc = $accAgainst = '';


        for($i=0; $i<$total_entry; $i++)
        {
            $account_type = AccountType::find($account_type_id[$i]);
            $inputs['account_group_id'] = $account_type->parent_id;
            if(!$account_type)
            {
                $status['message'] = 'Account Name not found';
            }
            $accAgainst .= ($accAgainst ? " & " : " ").$account_type->display_name;

            $inputs['amount'] = $amount[$i];
            $inputs['description'] .= $description[$i];
            $inputs['short_description'] = $description[$i];
            $inputs['account_type_id'] = $account_type_id[$i];
            $inputs['payment_method_id'] = $payment_method_id[$i];
            $inputs['cash_or_bank_id'] = '';
            $inputs['transaction_type']='dr';
            $inputs['account_name'] = $account_type->display_name;
            $inputs['to_account_name'] = $accountFrom->display_name;
            $inputs['against_account_name'] = $accountFrom->display_name;
            $inputs['against_account_type_id'] = $accountFrom->id;

            // Create Transaction Credit Amount
            $transactionCr = $this->createCreditAmount($inputs);
            $inputs['transaction_details_id'] = $transactionCr->id;

            $cr_amount = $cr_amount+$amount[$i];


            // Add Payment Details using payment Method based
            /*
             *  TODO: This will be use in Later for Re-Conciliation
             */
//                $this->checkingPaymentMethod($inputs, $i);

        }

        $inputs['transaction_type']='cr';
        $inputs['account_type_id'] = $accountFrom->id;
        $inputs['account_name'] = $accountFrom->display_name;
        $inputs['to_account_name'] = '';
        $inputs['against_account_name'] = $accAgainst;
        $inputs['amount'] = $cr_amount;

        // Create Transaction Debit Amount
        $transactionDr = $this->createDebitAmount($inputs);
        $inputs['transaction_details_id'] = $transactionDr->id;

        $transaction->amount = $cr_amount;
        $transaction->save();

        $status = ['type' => 'success', 'message' => $status['message']."<br/>".ucfirst($request->transaction_method).' save Successfully'];

        $status['transaction_code'] = $transaction->transaction_code;


            if($request->hasFile('attach'))
            {
                $file = $request->file('attach');

                $mediaData = $this->fileUploadWithDetails($file, $inputs['transaction_code'], null);
                $mediaData['model_id'] = $transaction->id;
                $mediaData['use_model'] = "Transactions";
                if($mediaData['file_name'] == null)
                {
                    $status = ['type' => 'success', 'message' => $status['message']." ".trans('common.unable_to_save_file')];

                }else{

                    MediaStore::create($mediaData);

                }
            }

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_save')." ".ucfirst($request->transaction_method)];
            DB::rollBack();
        }

        DB::commit();


        if(!isset($status['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.transaction.store-transaction')->with('status', $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = Transactions::authCompany()->where('id', $id)->first();

        if(!$modal)
        {
            return response()->json([
                'data' => [
                    'message' => 'Unable to delete'
                ]
            ], 400);
        }

        $this->transactionRevertAmount($modal->id);

        if($modal->sale_id && $modal->transaction_method == "Sales")
        {
            $sale = Sale::findOrFail($modal->sale_id);
             foreach($sale->sale_details as $value)
             {
                 $this->stock_report($value->item_id, $value->qty, 'delete sale', $sale->date);
                 $this->stockIn($value->item_id, $value->qty,'delete sale');

                 if(config('settings.warehouse'))
                 {
                     $warehouse =  WarehouseHistory::where('model', 'SaleDetail')->where('model_id', $value->id)->first();


                     $warehouseStock = new WarehouseStock($value->item_id, $warehouse->warehouse_id, 0, $value->date);
                     $warehouseStock->update_stock_report($value->item_id, '', $warehouse->warehouse_id);


                     $warehouse->delete();
                 }
             }
            $sale->sale_details()->delete();
            $sale->delete();
        }

        if($modal->purchase_id && $modal->transaction_method == "Purchases")
        {
            $purchase = Purchase::findOrFail($modal->purchase_id);
            foreach($purchase->purchase_details as $value)
            {
                $this->stock_report($value->item_id, $value->qty, 'delete purchase', $purchase->date);
                $this->stockOut($value->item_id, $value->qty,'delete purchase');

                if(config('settings.warehouse'))
                {
                    $warehouse = WarehouseHistory::where('model', 'PurchaseDetail')->where('model_id', $value->id)->first();

                    $warehouseStock = new WarehouseStock($value->item_id, $warehouse->warehouse_id, 0, $value->date);
                    $warehouseStock->update_stock_report($value->item_id, '', $warehouse->warehouse_id);


                    $warehouse->delete();
                }
            }
            $purchase->purchase_details()->delete();
            $purchase->delete();



        }



        if($modal->transaction_method == "Purchase Return")
        {
            $purchaseReturn = ReturnPurchase::where('transaction_id', $modal->id)->get();

            foreach ($purchaseReturn as  $value)
            {
                $purchase_date =$value->purchases->date;
                $value->delete();
                $this->update_stock_report($value->item_id, $purchase_date);
            }

        }

        if($modal->transaction_method == "Sale Return")
        {
            $saleReturn = SaleReturn::where('transaction_id', $modal->id)->get();
            foreach ($saleReturn as  $value)
            {
                $sale_date = $value->sales->date;
                $value->delete();
                $this->update_stock_report($value->item_id, $sale_date);

            }

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
            $data[$key]['company_id'] =  $value->company_id;
        }

        $modal->transaction_details()->delete();
        $modal->delete();

        foreach ( $data as $value) {
            $inputs = [];
            $inputs['account_type_id'] = $value['account_type_id'];
            $inputs['date'] =  $value['date'];

            $transactionCheck = TransactionDetail::where('date', $value['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->get();

            if(count($transactionCheck)<1)
            {
                AccountHeadDayWiseBalance::where('date', $inputs['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->delete();
            }

            $this->updateAccountHeadBalanceByDate($inputs);
            $this->updateSharerBalance($inputs);
            $this->updateCashBankBalance($inputs);
        }


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
            'cash_or_bank_id' => 'required',
            'date' => 'required'
        ];

        return $rules;
    }

    protected function assignedCheck(){

        if(empty(Auth::user()->company_id))
        {
            $status = ['type'=>'danger','message'=>trans('common.confirm_your_company_name')];
            return redirect('member/set-users-company')->with('status', $status);
        }

        if(empty(Auth::user()->company->fiscal_year_id))
        {
            $status = ['type'=>'danger','message'=>'Fiscal Year is not set for your Company. Please confirm your Fiscal Year'];
            return redirect('member/company-fiscal-year')->with('status', $status);
        }

        return "Success";

    }



}