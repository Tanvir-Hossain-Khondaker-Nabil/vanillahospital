<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 2/5/2023
 * Time: 1:14 PM
 */

namespace App\Http\Services;


use App\Http\Traits\SharerCashAndAccount;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\SupplierOrCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerSave
{
    use SharerCashAndAccount, TransactionTrait;

    public function create_customer( $quotationer)
    {
        // dd($quotationer['company_name']);
        $request = new Request();
        $inputs = [];
        $request['name'] = $request['display_name'] = $quotationer->company_name?? $quotationer['company_name'];
        $inputs['name'] = $inputs['display_name'] = $quotationer->company_name?? $quotationer['company_name'];
        $request['phone'] = $inputs['phone'] = $quotationer->contact_no ?? $quotationer['contact_no'];
        $inputs['address'] = $quotationer->address_1 ?? $quotationer['address_1'];
        $request['member_id'] = $inputs['member_id'] = Auth::user()->member_id;
        $request['company_id'] = $inputs['company_id'] = Auth::user()->company_id;
        $request['initial_date'] = date("Y")."-01-01";
        $inputs['customer_type'] = $type = 'customer';
        $inputs['status'] = 'active';
        $sharer = SupplierOrCustomer::create($inputs);

        $this->saveAccountTransaction($request, $inputs, $sharer);

        return $sharer;
    }

    public function create_pos_customer($phone)
    {

        // dd($phone);
        $request = new Request();
        $inputs = [];
        $request['name'] = $request['display_name'] = $phone;
        $inputs['name'] = $inputs['display_name'] = $phone;
        $request['phone'] = $inputs['phone'] = $phone;
        $request['member_id'] = $inputs['member_id'] = Auth::user()->member_id;
        $request['company_id'] = $inputs['company_id'] = Auth::user()->company_id;
        $request['initial_date'] = date("Y")."-01-01";
        $inputs['customer_type'] = $type = 'customer';
        $inputs['status'] = 'active';

        $sharer = SupplierOrCustomer::create($inputs);
        $this->saveAccountTransaction($request, $inputs, $sharer);

        $sharer->membership_no = $this->membership_generate();
        $sharer->save();

        return $sharer;
    }


    public function create_store_customer($request, $id=null, $response=false)
    {

        $inputs = [];
        $request['name'] = $request['display_name'] = $request->store_name;
        $inputs['name'] = $inputs['display_name'] = $request->store_name;
        $request['phone'] = $inputs['phone'] = $request->mobile_no;
        $request['member_id'] = $inputs['member_id'] = Auth::user()->member_id;
        $request['company_id'] = $inputs['company_id'] = Auth::user()->company_id;
        $request['initial_date'] = date("Y")."-01-01";
        $inputs['customer_type'] = $type = 'customer';
        $inputs['status'] = 'active';

        if(!$id)
        {
            $sharer = SupplierOrCustomer::create($inputs);
        } else {
            $sharer = SupplierOrCustomer::find($id);
            $r = $sharer->update($inputs);
        }


        $this->saveAccountTransaction($request, $inputs, $sharer, $response);

        return $sharer;
    }

    public function saveAccountTransaction($request, $inputs, $sharer, $response="")
    {

        $inputs['initial_balance'] = 0;
        $inputs['amount_type'] = "dr";

        // dd($inputs,$sharer);

        if($sharer->account_type_id)
            $set_account_head = $this->set_account_head($request, $sharer->account_type_id, $response);
        else
            $set_account_head = $this->set_account_head($request, '', $response);


        if($sharer->cash_or_bank_id)
            $set_cash_bank = $this->set_cash_or_bank($request, $sharer->cash_or_bank_id, $response);
        else
            $set_cash_bank = $this->set_cash_or_bank($request, '', $response);




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
            //  dd($set_transaction);
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
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Initial';
            $inputs['description'] = '';


            if($inputs['amount_type'] == "credit") {
                $inputs['transaction_type'] = 'cr';
                $this->createCreditAmount($inputs);
            }else{

                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);
            }


        }
    }

    public function membership_generate()
    {
        $todate = Carbon::today()->format('Y-m-d');
        $todayCustomerCount = SupplierOrCustomer::whereDate('created_at', $todate)->count();

        $code = sprintf("%03d", $todayCustomerCount).date("dmY");

        return $code;
    }
}