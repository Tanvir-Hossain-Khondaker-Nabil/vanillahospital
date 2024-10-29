<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 29-Dec-19
 * Time: 4:10 PM
 */

namespace App\Http\Traits;


use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

trait SharerCashAndAccount
{
    use ValidatesRequests;

    public function set_cash_or_bank(Request $request, $id='', $response = "")
    {
        $account_type = AccountType::where('display_name', $request->display_name)->first();

        $data = [];
        $data['title'] = $request['title'] = $request->display_name;
        $data['contact_person'] = $request['contact_person'] = $request->display_name;
        $data['phone'] = $request->phone;
        $data['member_id'] = $request->member_id;

        if(!$response)
            $this->validate($request, $this->cashBankAccountRules($id));
        else
        {
            $validator = \Validator::make($request->all(), $this->cashBankAccountRules($id));

            if($validator->fails())
                return response()->json($validator->errors(), 422);
        }


        $data['account_type_id'] = $account_type->id;

        if($id){
            $data['updated_by'] = $request->updated_by;
            $CashOrBankAccount =  CashOrBankAccount::find($id);
            $CashOrBankAccount->update($data);

            return $CashOrBankAccount;

        } else{
            $data['created_by'] = $request->created_by;
            return CashOrBankAccount::create($data);
        }
    }

    public function set_account_head(Request $request, $id='', $response = "")
    {
        $data = [];
        $data['display_name'] = $request['display_name']  = $request->name;
        $data['name'] = $request['name'] = snake_case($request->name );
        
        if(isset($request->member_id))
            $data['member_id'] = $request->member_id;

        if(isset($request->parent_id))
            $data['parent_id'] = $request->parent_id;


        if(!$response)
            $this->validate($request, $this->accountTypeRules($id));
        else
        {
            $validator = \Validator::make($request->all(), $this->accountTypeRules($id));

            if($validator->fails())
                return response()->json($validator->errors(), 422);
        }


        if($id)
        {
            $AccountType =  AccountType::find($id);
            $AccountType->update($data);

            return $AccountType;
        }
        else{
            return AccountType::create($data);
        }

    }


    private function accountTypeRules($id='')
    {
        if(is_null($id))
        {
            $data =  [
                'name' => 'required|unique:account_types,name',
                'display_name' => 'required|unique:account_types,display_name',
            ];
        }else{
            $data =  [
                'name' => 'required|unique:account_types,name,'.$id,
                'display_name' => 'required|unique:account_types,display_name,'.$id,
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
            $rules['title'] = 'required|unique:cash_or_bank_accounts,title';
        }else{
            $rules['title'] = 'required|unique:cash_or_bank_accounts,title,'.$id;
        }


        return $rules;
    }

}
