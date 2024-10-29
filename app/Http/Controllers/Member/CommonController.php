<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\SharerCashAndAccount;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\EmployeeInfo;
use App\Models\PaidCommission;
use App\Models\SaleCommission;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    use SharerCashAndAccount, TransactionTrait;

    protected $member_id, $created_by, $updated_by;

    public function saveAccountType(Request $request)
    {
        $rules = [
            'name' => 'required|unique:account_types,name'
        ];

        $customMessages = [
            'name.required' => 'Display Name field is required and Unique.'
        ];

        $inputs = $request->all();
        $inputs['name'] = $request['name'] = snake_case($request->display_name);

        $this->validate($request, $rules, $customMessages);

        $save = AccountType::create($inputs);

        $data = array(
            "okResponse" => true,
            "status" => 1,
            "value" => null,
            "values" => $save
        );

        header('Content-Type: application/json');

        echo json_encode($data);
    }

    public function payerSearch(Request $request)
    {
        $customer_type = $request->customer_type;
        $sharers = new SupplierOrCustomer();
        if($customer_type=="customer")
        {
            $sharers = $sharers->onlyCustomers();
        }elseif($customer_type=="both") {
            $sharers = $sharers->where('customer_type','both');
        }elseif($customer_type=="supplier") {
            $sharers = $sharers->onlySuppliers();
        }

        $sharers  = $sharers->get()->pluck('name', 'id');

        $data = array(
            "okResponse" => true,
            "status" => 1,
            "value" => null,
            "values" => $sharers
        );

        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function saveCustomer(Request $request)
    {
        $rules = [
            'name' => 'required',
//            'phone' => 'required|unique:suppliers_or_customers,phone',
            'address' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Name field is required.',
//            'phone.required' => 'Phone field is required and Unique.'
        ];

        $validator = \Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails())
            return response()->json($validator->errors(), 422);

        $customer  = SupplierOrCustomer::where('name', $request->name)->first();
        $customerCount  = SupplierOrCustomer::count();

        $hasCustomer = false;

        if($customer)
        {
            $customerCheck  = SupplierOrCustomer::where('name', $request->name)->where('phone', $request->phone)->first();
            $request['customer_name'] = $request->name;
            $request['name'] = $request->name." ".($customerCount+2);
            if($customerCheck)
            {
                $hasCustomer = true;
            }
        }else{

            $request['customer_name'] = $request->name;
        }


        if($hasCustomer)
        {
            $save = $customerCheck;
        }else{
            $save = $this->save($request);
        }

//        $save = SupplierOrCustomer::create($inputs);


        if( method_exists($save, 'status') && $save->status() == 422)
        {
            $data = array(
                "okResponse" => false,
                "status" => 0,
                "value" => null,
                "values" => $save
            );
        }else{
            $data = array(
                "okResponse" => true,
                "status" => 1,
                "value" => null,
                "values" => $save
            );
        }


        header('Content-Type: application/json');

        echo json_encode($data);
    }


    public function save($request)
    {
        $this->member_id = Auth::user()->member_id;
        $this->created_by = Auth::user()->created_by;
        $this->updated_by = Auth::user()->updated_by;


        DB::beginTransaction();
        try{

            $sharer = new SupplierOrCustomer();
            $sharer->name = $request->customer_name;
            $sharer->phone = $request->phone;

            if(!isset($request->customer_id))
            {
                $sharer->member_id = $this->member_id;
                $sharer->status = "active";
                $sharer->customer_type = "customer";
                $sharer->customer_initial_balance = 0;
                $sharer->created_by = $request['created_by'] = $this->created_by;


                $account_head = $this->set_account_head($request, '', 'json');


                if( method_exists($account_head, 'status') && $account_head->status() == 422)
                {
                    return $account_head;
                }


                $cash_or_bank = $this->set_cash_or_bank($request, '','json');

                if( method_exists($cash_or_bank, 'status') && $cash_or_bank->status() == 422)
                {
                    return $cash_or_bank;
                }


                $sharer->account_type_id = $account_head->id;
                $sharer->cash_or_bank_id = $cash_or_bank->id;

            }else{

                $sharer = $sharer->find($request->customer_id);

                $sharer->updated_by = $request['updated_by'] = $this->updated_by;

                $this->set_account_head($request, $sharer->account_type_id, 'json');
                $this->set_cash_or_bank($request, $sharer->cash_or_bank_id, 'json');

            }



            if(isset($request->email))
                $sharer->email = $request->email;

            if(isset($request->address))
                $sharer->address = $request->address;

            if(isset($request->description))
                $sharer->description = $request->description;

            $sharer->save();


            $bf_balance = TransactionDetail::where('account_type_id', $sharer->account_type_id)->first();
//        $bf_balance = $bf_balance->transaction()->where('transaction_method', 'Initial')->first();


            $inputs['member_id'] = $this->member_id;
            $inputs['created_by'] = $this->created_by;
            $inputs['company_id'] = Auth::user()->company_id;

            if(!$bf_balance) {

                $inputs['cash_or_bank_id'] = $sharer->cash_or_bank_id;
                $inputs['supplier_id'] = $sharer->id;
                $inputs['date'] = Carbon::today();
                $inputs['transaction_method'] = 'Initial';
                $inputs['amount'] = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
                $set_transaction = $this->set_transaction($inputs);
                //
                $account_type = new AccountType();
                $account_type = $account_type->where('name', 'sales')->first();

                $inputs['account_name'] = $sharer->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['sharer_name'] = $sharer->name;
                $inputs['account_group_id'] = $sharer->account_type->parent_id;
                $inputs['account_type_id'] = $sharer->account_type->id;
                $inputs['against_account_type_id'] = $account_type->id;
                $inputs['against_account_name'] = "Balance B/F";
                //                $inputs['against_account_name'] = $account_type->display_name;
                $inputs['payment_method_id'] = 1;
                $inputs['transaction_id'] = $set_transaction;
                $inputs['transaction_method'] = 'Initial';
                $inputs['description'] = 'B/F';


                $inputs['transaction_type'] = 'cr';
                $transactionCr = $this->createDebitAmount($inputs);
            }else{

                $transactions = Transactions::findOrFail($bf_balance->transaction_id);
                $this->transactionRevertAmount($transactions->id, $inputs);
                $transactions->amount = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
                $bf_balance->amount = !isset($inputs['initial_balance']) || empty($inputs['initial_balance']) ? 0 : $inputs['initial_balance'];
                $bf_balance->save();
                $transactions->save();
            }

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to save '];
            DB::rollBack();
        }

        DB::commit();


        return $sharer ? $sharer : 0;
    }

    public function employee_details(Request $request)
    {
        $data['employee'] = $emp = EmployeeInfo::find($request->employee_id);
        $data['sale_commission']  = SaleCommission::where('employee_id',$request->employee_id)->sum('commission_amount');
        $data['paid_commission']  = PaidCommission::where('employee_id',$request->employee_id)->sum('amount');

        if($data['employee'])
        {
            $data['status'] = "success";
        }else{
            $data['status'] = "danger";
        }

        return response()->json($data);
    }
}
