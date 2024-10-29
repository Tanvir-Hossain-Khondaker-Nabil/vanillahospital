<?php

namespace App\Http\Controllers\Member;

use App\DataTables\CustomersDataTable;
use App\DataTables\SharerGLAccountsDataTable;
use App\DataTables\SuppliersDataTable;
use App\DataTables\BrokerDataTable;
use App\Http\Traits\FileUploadTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\District;
use App\Models\Division;
use App\Models\DocumentType;
use App\Models\SharerSubmittedDocument;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use App\Rules\SharerCompositeUnique;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SharerController extends Controller
{
    use TransactionTrait, FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SharerGLAccountsDataTable $dataTable)
    {
        return $dataTable->render('admin.account_types.sharers');
    }

    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function customer_list( CustomersDataTable $dataTable)
    {

        return $dataTable->render('member.suppliers_or_customers.customers');
    }

    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function broker_list(BrokerDataTable $dataTable)
    {
        return $dataTable->render('member.suppliers_or_customers.brokers');
    }

    /**
     * Display a listing of the Supplier.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplier_list( SuppliersDataTable $dataTable)
    {
        return $dataTable->render('member.suppliers_or_customers.suppliers');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( $type )
    {
        $data['sharer_type'] = ucfirst($type);
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd','id');
        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');
        $data['document_types'] = DocumentType::get()->pluck( 'name','id');
        $data['managers'] = User::membersUser()->systemUser()->get()->pluck( 'uc_full_name','id');

        return view('member.suppliers_or_customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $request['composite_unique'] = $this->checkSharerCompositeUnique($request);
        $request['company_id'] = Auth::user()->company_id;
        $request['member_id'] = Auth::user()->member_id;

        $customMessages = [
            'name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules(), $customMessages);

        $type = $request->customer_type;

        if($type == "Broker")
        {
            $request['amount_type'] = "debit";
            $request['initial_balance'] = 0;
            $request['initial_date'] = date("Y-m-d");
            $request['bf_text'] = '';
        }


        $inputs = $request->all();
        $inputs['customer_type'] = strtolower($type);
        $request->both == 1 ? $inputs['customer_type'] = 'both' : '';



        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $destinationPath = 'files';
            $upload = $this->fileUploadWithDetails($file, $destinationPath);
            $inputs['file'] = $upload['file_store_path'] . "/" . $upload['file_name'];
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $options['width'] = 100;
            $options['height'] = 100;
            $destinationPath = 'sharers';
            $upload = $this->fileUpload($file, $destinationPath, $options);
            $inputs['photo'] = $upload;
        }

        $submitted_documents = $request->submitted_documents;


//        DB::beginTransaction();
//        try{

        $sharer = SupplierOrCustomer::create($inputs);
        $sharer->name = $request->name;
        $sharer->phone = $request->phone;
        $sharer->email = $request->email;
        $sharer->address = $request->address;
        $sharer->is_blacklist = $request->is_blacklist;

        if ($type == "customer")
            $sharer->customer_initial_balance = $request->initial_balance;
        elseif ($type == "supplier")
            $sharer->supplier_initial_balance = $request->initial_balance;
        else {
            $sharer->customer_initial_balance = $request->initial_balance;
            $sharer->supplier_initial_balance = $request->initial_balance;
        }

//        $sharer->submitted_document()->saveMany($submitted_documents);

        if ($submitted_documents){
            foreach ($submitted_documents as $value) {
                $submitted_document = new SharerSubmittedDocument();
                $submitted_document->sharer_id = $sharer->id;
                $submitted_document->document_type_id = $value;
                $submitted_document->save();
            }
         }

            $request['name'] = $sharer->id." ".$sharer->name;
            $request['contact_person'] = $request['display_name'] = $sharer->name;



//            if($request->account_head)
//            {
                $set_account_head = $this->set_account_head($request);
//            }
//
//            if($request->cash_bank)
//            {
                $set_cash_bank = $this->set_cash_or_bank($request);
//            }


            if( $set_account_head->id > 0 && $set_cash_bank->id > 0)
            {

                $sharer->account_type_id = $set_account_head->id;
                $sharer->cash_or_bank_id = $set_cash_bank->id;
                $sharer->save();

                $inputs['cash_or_bank_id'] = $set_cash_bank->id;
                $inputs['supplier_id'] = $sharer->id;
                $inputs['date'] = db_date_format($request->initial_date);
                $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
                $set_transaction = $this->set_transaction($inputs);

                $account_type = new AccountType();

                if($inputs['amount_type'] == "credit") {
                    
                    $account_type = $account_type->where('name', 'purchase')->first();
                }else{
                    $account_type = $account_type->where('name', 'sales')->first();
                }

                $inputs['account_name'] = $set_account_head->display_name;
                $inputs['to_account_name'] = '';
                $inputs['sharer_name'] = $sharer->name;
                $inputs['account_group_id'] = $set_account_head->parent_id;
                $inputs['account_type_id'] = $set_account_head->id;
                $inputs['against_account_type_id'] = $account_type->id;
                $inputs['against_account_name'] = "Balance B/F";
//                $inputs['against_account_name'] = $account_type->display_name;
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

                // $status = ['type' => 'success', 'message' => $type.' Added Successfully'];
                $status = ['type' => 'success', 'message' => trans('common.'.strtolower($type)).' '.trans('common.added_succesfully')];

            }else{

                // $status = ['type' => 'danger', 'message' => 'Unable to Add '.$type];
                $status = ['type' => 'danger', 'message' => trans('common.unable_to_add_'.strtolower($type))];
            }


//        }catch (\Exception $e){
//
//            $status = ['type' => 'danger', 'message' => 'Unable to Add '.$type];
//            DB::rollBack();
//        }
//
//        DB::commit();

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
        $data['sharer'] = $sharer = SupplierOrCustomer::where('id', $id)->authCompany()->authMember()->firstOrFail();

        $data['submitted_documents'] = $sharer->submitted_document;

       /* if(!$sharer)
        {
            $status = ['type' => 'danger', 'message' => 'You don\'t have this Id Supplier/Customer' ];
            return redirect()->back()->with('status', $status);
        }*/

        return view('member.suppliers_or_customers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['model'] = $sharer = SupplierOrCustomer::find($id);
        $data['divisions'] = Division::get()->pluck('display_name_bd','id');
        $data['districts'] = District::get()->pluck('display_name_bd','id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd','id');
        $data['unions'] = Union::get()->pluck( 'display_name_bd','id');
        $data['areas'] = Area::get()->pluck( 'display_name_bd','id');
        $data['managers'] = User::membersUser()->systemUser()->get()->pluck( 'uc_full_name','id');
        $data['document_types'] = DocumentType::get()->pluck( 'name','id');
        $data['submitted_documents'] = $sharer->submitted_document->pluck('document_type_id')->toArray();

//        dd($data['submittes_documents']);
        if(!$sharer)
        {
            $status = ['type' => 'danger', 'message' => trans('common.dont_have_any_data')];
            return back()->with('status', $status);
        }


        $data['bf_balance'] = $bf_balance= TransactionDetail::where('account_type_id', $sharer->account_type_id)
            ->whereHas('transaction', function ($query){
                $query->where('transaction_method', 'Initial');
            })->select('amount', 'date', 'description', 'transaction_type')->first();

        $data['sharer_type'] = ucfirst($sharer->customer_type);

        return view('member.suppliers_or_customers.edit', $data);
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
            'name.unique_with' => 'The :attribute is already exist for this company and Area'
        ];

        $this->validate($request, $this->getValidationRules($id), $customMessages);
        $newName = "";
        $type = $request->customer_type;

        if($type == "Broker")
        {
            $request['amount_type'] = "debit";
            $request['initial_balance'] = 0;
            $request['initial_date'] = date("Y-m-d");
            $request['bf_text'] = '';
        }


        $sharer =  SupplierOrCustomer::find($id);

        $inputs = $request->all();

        if(!isset($request->is_blacklist))
            $inputs['is_blacklist'] = 0;


        if($request->hasFile('file'))
        {
            $file = $request->file('file');

            $destinationPath = 'files';
            $upload = $this->fileUploadWithDetails($file, $destinationPath);
            $inputs['file'] = $upload['file_store_path']."/".$upload['file_name'];
        }

        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $options['width'] = 100;
            $options['height'] = 100;
            $destinationPath = 'sharers';
            $upload = $this->fileUpload($file, $destinationPath, $options);
            $inputs['photo'] = $upload;
        }

        if($sharer->name != $request->name)
        {
            $newName = $request->name;
        }


        $request['name'] = $sharer->id." ".$request->name;
        $request['contact_person'] = $request['display_name'] = $request->name;


        if($request->customer == 1 && $request->supplier == 1)
        {
            $inputs['customer_type'] = 'both';
            $inputs['customer_initial_balance'] = $request->initial_balance;
            $inputs['supplier_initial_balance'] = $request->initial_balance;
        }elseif($request->customer == 1){
            $inputs['customer_type'] = 'customer';
            $inputs['customer_initial_balance'] = $request->initial_balance;
        }elseif($request->supplier == 1){
            $inputs['customer_type'] = 'supplier';
            $inputs['supplier_initial_balance'] = $request->initial_balance;
        }else{
            $inputs['customer_type'] = 'broker';
            $inputs['customer_initial_balance'] = $request->initial_balance;
            $inputs['supplier_initial_balance'] = $request->initial_balance;
        }



        $sharer->update($inputs);

        $set_account_head = $this->set_account_head($request, $sharer->account_type_id);
        $set_cash_bank = $this->set_cash_or_bank($request, $sharer->cash_or_bank_id);

        if( $set_account_head->id > 0 && $set_cash_bank->id > 0) {

            $sharer->account_type_id = $set_account_head->id;
            $sharer->cash_or_bank_id = $set_cash_bank->id;
            $sharer->save();
        }


        DB::beginTransaction();
        try{

            if($inputs['amount_type'] == "credit") {
                $inputs['transaction_type'] = 'cr';
            }else{
                $inputs['transaction_type'] = 'dr';
            }

        $submitted_documents = $request->submitted_documents;
        if($submitted_documents) {

            SharerSubmittedDocument::where('sharer_id', $sharer->id)->delete();
            foreach ($submitted_documents as $value) {
                $submitted_document = new SharerSubmittedDocument();
                $submitted_document->sharer_id = $sharer->id;
                $submitted_document->document_type_id = $value;
                $submitted_document->save();
            }
        }

            $bf_balance = TransactionDetail::where('account_type_id', $sharer->account_type_id)
                ->whereHas('transaction', function ($query){
                    $query->where('transaction_method', 'Initial');
                })->first();

            if ($bf_balance)
                $transactions = Transactions::findOrFail($bf_balance->transaction_id);

            if($bf_balance){
                $this->transactionRevertAmount($transactions->id);
                $transactions->date = $bf_balance->date = db_date_format($request->initial_date);
                $transactions->amount = $request->initial_balance;
                $bf_balance->amount = $request->initial_balance;

                if($inputs['amount_type'] == "credit") {
                    $bf_balance->transaction_type = 'cr';
                }else{
                    $bf_balance->transaction_type = 'dr';
                }

                $bf_balance->save();
                $transactions->save();

                $inputs['account_type_id'] = $sharer->account_type_id;
                $inputs['account_head_name'] = $sharer->account_type->display_name;
                $inputs['transaction_id'] = $transactions->id;
                $inputs['amount'] = $request->initial_balance;
                $inputs['date'] = $request->initial_date;

                $this->createAccountHeadBalanceHistory($inputs);
                $this->createAccountHeadDayWiseBalanceHistory($inputs);
                $this->updateAccountHeadBalanceByDate($inputs);
                $this->updateSharerBalance($inputs);
                $this->updateCashBankBalance($inputs);

            }else {

                $sharer =  SupplierOrCustomer::find($id);

                $inputs['cash_or_bank_id'] = $sharer->cash_or_bank_id;
                $inputs['supplier_id'] = $sharer->id;
                $inputs['date'] = db_date_format($request->initial_date);
                $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
                $set_transaction = $this->set_transaction($inputs);

                $account_type = new AccountType();

                if ($inputs['amount_type'] == "credit") {
                    $account_type = $account_type->where('name', 'purchase')->first();
                } else {
                    $account_type = $account_type->where('name', 'sales')->first();
                }

                $inputs['account_name'] = $sharer->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['sharer_name'] = $sharer->name;
                $inputs['account_group_id'] = $sharer->account_type->parent_id;
                $inputs['account_type_id'] = $sharer->account_type->id;
                $inputs['against_account_type_id'] = $account_type->id;
                $inputs['against_account_name'] = "Balance B/F";
                $inputs['payment_method_id'] = 1;
                $inputs['transaction_id'] = $set_transaction;
                $inputs['transaction_method'] = 'Initial';
                $inputs['description'] = $inputs['bf_text'];


                if ($inputs['amount_type'] == "credit") {
                    $inputs['transaction_type'] = 'cr';
                    $transactionCr = $this->createCreditAmount($inputs);
                } else {

                    $inputs['transaction_type'] = 'dr';
                    $transactionDr = $this->createDebitAmount($inputs);
                }
            }


            $status = ['type' => 'success', 'message' => trans('common.successfully_updated')];

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => trans('common.unable_to_update')];
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
        $modal = SupplierOrCustomer::findOrFail($id);

        $transactions = Transactions::where('supplier_id', $modal->id)->select('id')->get();

//        $transaction_details = TransactionDetail::where('account_type_id', $modal->account_type_id)->select('id')->count();
        $transaction_details = TransactionDetail::where('account_type_id', $modal->accout_type_id)->whereHas('transaction', function ($query){
            return $query->where('transaction_method', '!=','Initial');
        })->count();

        $notDelatedable = AccountType::where('id','<',46)->orWhereIn('id', [971,929,949,955,956,970])->pluck('id')->toArray();

        if(in_array($modal->account_type_id, $notDelatedable)) {
            return response()->json([
                'data' => [
                    'message' => trans('common.you_cant_delte_this_account_type_please_check_set_account_gl_maybe_changed')
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

            $transactions = Transactions::where('supplier_id', $modal->id)->select('id')->first();
            $this->transactionDestroy($transactions->id);

            $modal->cash_or_bank_id ? $modal->cash_bank()->delete() : null;
            $modal->account_type_id ? $modal->account_type()->delete() : null;
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
            'file' => 'file|mimes:jpeg,jpg,png,pdf',
            'customer_type' => 'required',
            'status' => 'required',
        ];


        if($id)
        {
            $rules['name'] = 'required|unique_with:suppliers_or_customers,name, phone,company_id,member_id,'.$id;
        }else{
//            $rules['composite_unique'] = [new SharerCompositeUnique()];

            $rules['name'] = 'required|unique_with:suppliers_or_customers,name, phone,company_id,member_id';
        }

        return $rules;
    }


    private function set_cash_or_bank(Request $request, $id='')
    {
        $account_type = AccountType::where('display_name', $request->display_name)->first();

        $data = [];
        $data['title'] = $request['title'] = $request->display_name;
        $data['contact_person'] = $request['contact_person'] = $request->contact_person;
        $data['phone'] = $request->phone;

        $this->validate($request, $this->cashBankAccountRules($id));

        $data['account_type_id'] = $account_type->id;

        if($id)
        {
            $cashs = CashOrBankAccount::find($id);
             $cashs->update($data);
            return $cashs;
        }
        else
            return CashOrBankAccount::create($data);

    }

    private function set_account_head(Request $request, $id='')
    {
        $data = [];
        $data['display_name'] =  $request->display_name;
        $data['name'] = snake_case($request->name);

        $this->validate($request, $this->accountTypeRules($id));

        if($id) {
            $account = AccountType::find($id);
            $account->update($data);
            return  $account;
        }
        else
           return AccountType::create($data);
    }


    private function accountTypeRules($id='')
    {
        if(is_null($id))
        {
            $data =  [
                'name' => 'required|unique_with:account_types,name, company_id, member_id',
                'display_name' => 'required|unique_with:account_types,display_name,company_id, member_id',
            ];
        }else{
            $data =  [
                // 'name' => 'required|unique_with:account_types,name, company_id, member_id,'.$id,
                'display_name' => 'required|unique_with:account_types,display_name, company_id, member_id,'.$id,
            ];
        }

        return $data;
    }

    private function cashBankAccountRules($id='')
    {
        $rules = [
            'contact_person' => 'required',
            'phone' => 'required'
        ];

        if($id=='') {
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id';
        }else{
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id,'.$id;
        }


        return $rules;
    }


    private function checkSharerCompositeUnique($request)
    {
        $sharer = SupplierOrCustomer::where('name', $request->name)
            ->where('phone', $request->phone)
            ->where('company_id', $request->company_id)
            ->where('union_id', $request->union_id)->first();

        if($sharer)
            $result = true;
        else
            $result = false;

        return $result;
    }



}