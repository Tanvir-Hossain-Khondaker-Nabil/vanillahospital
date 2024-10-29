<?php

namespace App\Http\Controllers\Member;

use App\Models\CashOrBankAccount;
use App\Models\Company;
use App\Models\FiscalYear;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{

    public function company_fiscal_year()
    {
        $data = [];
        $data['companies'] = Company::active()->authMember()->get()->pluck('company_name', 'id');
        $data['company_fiscal_years'] = Company::active()->authMember()->paginate(10);
        $data['fiscal_year'] = FiscalYear::active()->authCompany()->authMember()->get()->pluck('fiscal_year_details', 'id');
        return view('member.settings.company_fiscal_year', $data);
    }

    public function set_company_fiscal_year(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'fiscal_year_id' => 'required'
        ];

        $this->validate($request, $rules);

        $company  = Company::authMember()->where('id', $request->company_id)->first();
        $company->fiscal_year_id = $request->fiscal_year_id;
        $company->save();
        $status = ['type' => 'success', 'message' => 'Successfully Fiscal Year Add Company '.$company->company_name];
        return back()->with('status', $status);
    }

    public function general_settings()
    {

        $cashs = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->pluck('title', 'account_type_id');
        $memberCode = Auth::user()->member->member_code;
        $members = Member::where('member_code', '=', $memberCode)->first();

        $data = [];
        $data['print_page_option'] = [
            'pos' => "POS Print",
            'default' => "Default A4 Print"
        ];
        $data['memberInfo'] = $members;
        $data['cashs'] = $cashs;

        return view('member.settings.general_settings', $data);
    }

    public function set_print_page_setup(Request $request)
    {
        $settings = Setting::where('key', '=', 'print_page_setup')->first();

        $input = [];
        if( isset($request->print_page_setup) && !$settings)
        {
            $input['key'] = 'print_page_setup';
            $input['value'] = $request->print_page_setup;
            $input['company_id'] = Auth::user()->company_id;
            Setting::create($input);
        }else{
            $input['value'] = $request->print_page_setup;
            $settings->update($input);
        }

        $status = ['type' => 'success', 'message' => 'Successfully Print Page setup Done'];
        return back()->with('status', $status);
    }


    public function set_cash_setup(Request $request)
    {
        $settings = Setting::where('key', '=', 'cash_account_id')->authCompany()->first();
        $Cash_settings = Setting::where('key', '=', 'cash_bank_id')->authCompany()->first();

        $cashs = CashOrBankAccount::where('account_type_id', $request->cash_account_id)->authCompany()->first();

        $input = [];
        if( isset($request->cash_account_id) && !$settings)
        {
            $input['key'] = 'cash_account_id';
            $input['value'] = $request->cash_account_id;
            $input['company_id'] = Auth::user()->company_id;
            Setting::create($input);

            $input = [];
            $input['key'] = 'cash_bank_id';
            $input['value'] = $cashs->id;
            $input['company_id'] = Auth::user()->company_id;
            Setting::create($input);
        }else{
            $input['value'] = $request->cash_account_id;
            $settings->update($input);

            $input = [];
            $input['value'] = $cashs->id;
            $Cash_settings->update($input);
        }

        $status = ['type' => 'success', 'message' => 'Successfully Cash setup Done'];
        return back()->with('status', $status);
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        unset($inputs['_token']);

        foreach ($inputs as $key => $value)
        {
            $settings = Setting::where('key', '=', $key)->authCompany()->first();


            if(!$settings)
            {
                $input = [];
                $input['key'] = $key;
                $input['value'] = $value;
                $input['company_id'] = Auth::user()->company_id;
                Setting::create($input);
            }else{

                $settings->update(['value'=>$value]);
            }

        }

        $status = ['type' => 'success', 'message' => 'Successfully Settings Done'];

        return back()->with('status', $status);
    }

}
