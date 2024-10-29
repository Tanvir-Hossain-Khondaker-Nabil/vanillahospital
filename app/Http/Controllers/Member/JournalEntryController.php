<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\TransactionDetailsTrait;
use App\Http\Traits\TransactionHistoryTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\JournalEntryDetail;
use App\Models\MediaStore;
use App\Models\PaymentMethod;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    use TransactionHistoryTrait, TransactionTrait, FileUploadTrait, TransactionDetailsTrait, CompanyInfoTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
            $order = 'DESC', '=','transaction_method','Journal Entry', $multiple_condition
        );

        $data['accounts'] = AccountType::authMember()->active()->get()->pluck('display_name','id');

        return view('member.journal-entry.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $data['transaction_categories_id'] = AccountType::authCompany()->get()->pluck('account_code', 'id')->toArray();
        $data['transaction_categories'] =  AccountType::authCompany()->get()->pluck('display_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name','id');

        return view('member.journal-entry.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            $save_transaction->notation = $inputs['notation'];
            $save_transaction->transaction_method = $inputs['transaction_method'] = "Journal Entry";
            $save_transaction->save();


            $inputs['ip_address'] = $request->ip();
            $inputs['browser_history'] = $request->header('User-Agent');
            $inputs['flag'] = "add";

            $account_type_id = $request->account_type_id;
            $payment_method_id = $request->payment_method_id;
            $transaction_type = $request->transaction_type;
            $amount = $request->amount;
            $description = $request->description;
            $inputs['document_date'] = db_date_format($request->document_date);
            $inputs['event_date'] = db_date_format($request->event_date);

            $dr_amount = 0;
            $cr_amount = 0;
            $credit_account_name = '';

            $inputs['transaction_id'] = $save_transaction->id;
            $type = $inputs['transaction_method'];

            $acc = $accAgainst = '';
            for($i=0; $i<$total_entry; $i++)
            {
                $account_type = AccountType::find($account_type_id[$i]);
                $inputs['transaction_type'] = $transaction_type[$i];
                if($inputs['transaction_type']=='dr')
                {
                    $acc .= ($acc ? " & " : " ").$account_type->display_name ;
                }else{
                    $accAgainst .= ($accAgainst ? " & " : " ").$account_type->display_name;
                }

            }


        for($i=0; $i<$total_entry; $i++)
        {
            $account_type = AccountType::find($account_type_id[$i]);
            $inputs['account_group_id'] = $account_type->parent_id;
            if(!$account_type)
            {
                $status['message'] = trans('common.account_name_not_found');
            }

            $inputs['amount'] = $amount[$i];
            $inputs['description'] = $description[$i];
            $inputs['short_description'] = $description[$i];
            $inputs['account_type_id'] = $account_type_id[$i];
            $inputs['payment_method_id'] = $payment_method_id[$i];
            $inputs['transaction_type'] = $transaction_type[$i];
            $inputs['cash_or_bank_id'] = '';
            if($inputs['transaction_type']=='dr')
            {
                $inputs['account_name'] = $account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['against_account_name'] = $accAgainst;

                // Create Transaction Debit Amount
                $transactionDr = $this->createDebitAmount($inputs);
                $inputs['transaction_details_id'] = $transactionDr->id;

                $dr_amount = $dr_amount+$amount[$i];
            }else{
                $inputs['account_name'] = $account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['against_account_name'] = $acc;
                // Create Transaction Credit Amount
                $transactionCr = $this->createCreditAmount($inputs);
                $inputs['transaction_details_id'] = $transactionCr->id;

                $cr_amount = $cr_amount+$amount[$i];

            }

            JournalEntryDetail::create($inputs);

            // Add Payment Details using payment Method based
            /*
             *  TODO: This will be use in Later for Re-Conciliation
             */
//                $this->checkingPaymentMethod($inputs, $i);

        }


            $save_transaction->amount = $dr_amount;
            $save_transaction->save();

            $status = ['type' => 'success', 'message' => $status['message']."<br/>".ucfirst($request->transaction_method)." ".trans('common.save_successfully')];

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

            $status = ['type' => 'danger', 'message' =>trans('common.unable_to_save')." ".ucfirst($request->transaction_method)];
            DB::rollBack();
        }

        DB::commit();


        if(!isset($status['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.journal-entry.store-transaction')->with('status', $status);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {

        $data['transaction'] = $transaction  = Transactions::where('transaction_code', $code)->get();

        if(count($transaction)<1)
        {
            $status = ['type' => 'danger', 'message' =>trans('common.transaction_code').': '.$code." ".trans('common.not_found')];
            return back()->with('status', $status);
        }

        $transaction = Transactions::where('transaction_code', $code)->first();
        $modal = JournalEntryDetail::where('transaction_id', $transaction->id)->get();

        $data['journal']['transaction'] = $modal;
        $data['journal']['date'] = $transaction->date_format;
        $data['journal']['document_date'] = $modal[0]->document_date_format;
        $data['journal']['event_date'] = $modal[0]->event_date_format;
        $data['journal']['source_reference'] = $modal[0]->source_reference;
        $data['journal']['method'] = $transaction->transaction_method;
        $data['journal']['transaction_code'] = $transaction->transaction_code;
        $data['journal']['entry_by'] = $transaction->created_user->full_name;
        $data['journal']['notation'] = $transaction->notation;

        return view('member.journal-entry.show', $data);
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function print_journal_entry($code)
    {
        $data['transaction'] = $transaction  = Transactions::where('transaction_code', $code)->get();

        if(count($transaction)<1)
        {
            $status = ['type' => 'danger', 'message' =>trans('common.transaction_code').': '.$code." ".trans('common.not_found')];
            return back()->with('status', $status);
        }

        $transaction = Transactions::where('transaction_code', $code)->first();
        $modal = JournalEntryDetail::where('transaction_id', $transaction->id)->get();

        $data['journal']['transaction'] = $modal;
        $data['journal']['date'] = $transaction->date_format;
        $data['journal']['document_date'] = $modal[0]->document_date_format;
        $data['journal']['event_date'] = $modal[0]->event_date_format;
        $data['journal']['source_reference'] = $modal[0]->source_reference;
        $data['journal']['method'] = $transaction->transaction_method;
        $data['journal']['transaction_code'] = $transaction->transaction_code;
        $data['journal']['entry_by'] = $transaction->created_user->full_name;
        $data['journal']['notation'] = $transaction->notation;

        $data = $this->company($data);
        $data['report_title'] = "Journal Entry Details";

        return view('member.journal-entry.print_journal_entry', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $data['transactions'] = Transactions::findOrFail($id);

        $data['journal_entries'] = JournalEntryDetail::where('transaction_id', $id)->first();

        $data['transaction_categories_id'] = AccountType::authCompany()->get()->pluck('account_code', 'id')->toArray();
        $data['transaction_categories'] =  AccountType::authCompany()->get()->pluck('display_name', 'id');
        $data['payment_methods'] = PaymentMethod::active()->get()->pluck('name','id');


        return view('member.journal-entry.edit', $data);
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
        $check = $this->assignedCheck();
        if( $check !='Success')
            return $check;

        $inputs = $request->all();
        $transaction = Transactions::findOrFail($id);

        $total_entry = count($request->account_type_id);

        $status['message'] = '';

        DB::beginTransaction();
        try{

        $inputs['date'] = db_date_format($inputs['date']);
        $transaction->date = $inputs['date'];
        $transaction->amount = 0.0;
        $transaction->notation = $inputs['notation'];
        $transaction->save();
        $inputs['transaction_method'] = "Journal Entry";

        $inputs['ip_address'] = $request->ip();
        $inputs['browser_history'] = $request->header('User-Agent');
        $inputs['flag'] = "update";

        $account_type_id = $request->account_type_id;
        $payment_method_id = $request->payment_method_id;
        $transaction_type = $request->transaction_type;
        $amount = $request->amount;
        $description = $request->description;
        $inputs['document_date'] = db_date_format($request->document_date);
        $inputs['event_date'] = db_date_format($request->event_date);

        $trans_details = TransactionDetail::where('transaction_id', $transaction->id)->get();
        $this->transactionRevertAmount($transaction->id);

        foreach ($trans_details as $value)
        {
            $data = [];
            foreach ( $transaction->transaction_details as $key=> $value1) {
                $data[$key]['account_type_id'] = $value1->account_type_id;
                $data[$key]['date'] =  $value1->date;
            }

            $journalEntries = JournalEntryDetail::where('transaction_details_id', $value->id)->first();
            $value->delete();
            $journalEntries->delete();

            foreach ( $data as $value2) {
                $input = [];
                $input['account_type_id'] = $value2['account_type_id'];
                $input['date'] =  $value2['date'];
                $this->updateAccountHeadBalanceByDate($input);
            }
        }

        $dr_amount = 0;
        $cr_amount = 0;
        $credit_account_name = '';

        $inputs['transaction_id'] = $transaction->id;

        $acc = $accAgainst = '';
        for($i=0; $i<$total_entry; $i++)
        {
            $account_type = AccountType::find($account_type_id[$i]);
            $inputs['transaction_type'] = $transaction_type[$i];
            if($inputs['transaction_type']=='dr')
            {
                $acc .= ($acc ? " & " : " ").$account_type->display_name ;
            }else{
                $accAgainst .= ($accAgainst ? " & " : " ").$account_type->display_name;
            }

        }

        for($i=0; $i<$total_entry; $i++)
        {
            $account_type = AccountType::find($account_type_id[$i]);
            $inputs['account_group_id'] = $account_type->parent_id;
            if(!$account_type)
            {
                $status['message'] = trans('common.account_name_not_found');
            }

            $inputs['amount'] = $amount[$i];
            $inputs['description'] = $description[$i];
            $inputs['short_description'] = $description[$i];
            $inputs['account_type_id'] = $account_type_id[$i];
            $inputs['payment_method_id'] = $payment_method_id[$i];
            $inputs['transaction_type'] = $transaction_type[$i];
            $inputs['cash_or_bank_id'] = '';
            if($inputs['transaction_type']=='dr')
            {
                $inputs['account_name'] = $account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['against_account_name'] = $accAgainst;

                // Create Transaction Debit Amount
                $transactionDr = $this->createDebitAmount($inputs);
                $inputs['transaction_details_id'] = $transactionDr->id;

                $dr_amount = $dr_amount+$amount[$i];
            }else{
                $inputs['account_name'] = $account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['against_account_name'] = $acc;
                // Create Transaction Credit Amount
                $transactionCr = $this->createCreditAmount($inputs);
                $inputs['transaction_details_id'] = $transactionCr->id;

                $cr_amount = $cr_amount+$amount[$i];

            }

            JournalEntryDetail::create($inputs);

            // Add Payment Details using payment Method based
            /*
             *  TODO: This will be use in Later for Re-Conciliation
             */
//                $this->checkingPaymentMethod($inputs, $i);

        }


        $transaction->amount = $dr_amount;
        $transaction->save();

        $status = ['type' => 'success', 'message' => $status['message']."<br/>".ucfirst($transaction->transaction_method).' save Successfully'];

        $status['transaction_code'] = $transaction->transaction_code;


        if($request->hasFile('attach'))
        {
            $file = $request->file('attach');

            $mediaData = $this->fileUploadWithDetails($file, $transaction->transaction_code, null);
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

            $status = ['type' => 'danger', 'message' =>trans('common.unable_to_save').' '.ucfirst($request->transaction_method)];
            DB::rollBack();
        }

        DB::commit();


        if(!isset($status['transaction_code']))
        {
            return back()->with('status', $status);
        }else{
            return view('member.journal-entry.store-transaction')->with('status', $status);
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
        //
    }

    protected function assignedCheck(){

        if(empty(Auth::user()->company_id))
        {
            $status = ['type'=>'danger','message'=>trans('common.confirm_your_company_name')];
            return redirect('member/set-users-company')->with('status', $status);
        }

        if(empty(Auth::user()->company->fiscal_year_id))
        {
            $status = ['type'=>'danger','message'=>trans('common.confirm_your_fiscal_year')];
            return redirect('member/company-fiscal-year')->with('status', $status);
        }

        return "Success";

    }



}