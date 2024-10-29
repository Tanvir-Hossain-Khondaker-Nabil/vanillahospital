<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\Item;
use App\Models\SupplierOrCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SharerImportController extends Controller
{
    use TransactionTrait;

    public function create()
    {
        return view('admin.import.sharer');
    }

    public function store(Request $request)
    {


        $this->validate($request, [
            'sharer_import_file' => 'required|mimes:xls,xlsx'
        ]);

        $sharer_type = $request->sharer_type;
        $importStatus = false;

        $lastCount= SupplierOrCustomer::count();
        if ($request->hasFile('sharer_import_file')) {

            Excel::load($request->file('sharer_import_file')->getRealPath(), function ($reader) use ($sharer_type, $request) {


                $data = $reader->toArray();

                foreach ($data as $key => $row) {
                    $array = [];
                    $array['name'] = $request['display_name'] = ucfirst($row['name']);
                    $request['name'] = ucfirst($row['name']);
                    $array['phone'] = $request['phone'] = $row['phone'];
                    $array['member_id'] = $request['member_id'] = Auth::user()->member_id;
                    $array['company_id'] = $request['company_id'] = Auth::user()->company_id;

                    $array['created_by'] = Auth::user()->id;
                    $array['customer_type'] = $request['customer_type'] = $sharer_type;
                    $array['status'] = 'active';

                    $customMessages = [
                        'name.unique_with' => 'The :attribute is already exist for this company and Area'
                    ];

                    $this->validate($request, $this->getValidationRules(), $customMessages);

                    DB::beginTransaction();
                    try {

                        $sharer = SupplierOrCustomer::create($array);

                        $set_account_head = $this->set_account_head($request);
                        $set_cash_bank = $this->set_cash_or_bank($request);


                        if ($set_account_head->id > 0 && $set_cash_bank->id > 0) {

                            $sharer->account_type_id = $set_account_head->id;
                            $sharer->cash_or_bank_id = $set_cash_bank->id;
                            $sharer->save();

                            $inputs = [];
                            $inputs['cash_or_bank_id'] = $set_cash_bank->id;
                            $inputs['supplier_id'] = $sharer->id;
                            $inputs['date'] = !isset($row['initial_date']) ? Carbon::today() : $row['initial_date'];
                            $inputs['amount'] = !isset($row['initial_balance']) || empty($row['initial_balance']) ? 0 : $row['initial_balance'];
                            $set_transaction = $this->set_transaction($inputs);

                            $account_type = new AccountType();

                            if ($sharer_type == "supplier") {
                                $account_type = $account_type->where('name', 'purchase')->first();
                            } else {
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
                            $inputs['description'] = 'bf text';


                            if ($sharer_type == "supplier") {
                                $inputs['transaction_type'] = 'cr';
                                $transactionCr = $this->createCreditAmount($inputs);
                            } else {

                                $inputs['transaction_type'] = 'dr';
                                $transactionDr = $this->createDebitAmount($inputs);
                            }
                        }
                        $importStatus = true;


                    } catch (\Exception $e) {


                        DB::rollBack();
                    }

                    DB::commit();
                }


            });


            if ($lastCount<SupplierOrCustomer::count()) {
                // $status = ['type' => 'success', 'message' => ucfirst($sharer_type) . ' import Successfully'];
                $status = ['type' => 'success', 'message' => trans('common.'.strtolower($sharer_type)) . ' '.trans('common.import_successfully')];
            }else{
                // $status = ['type' => 'danger', 'message' => 'Unable to import ' . ucfirst($sharer_type)];
                $status = ['type' => 'danger', 'message' => trans('common.unable_to_import_'.strtolower($sharer_type))];
            }

        } else {

            // $status = ['type' => 'danger', 'message' => 'Unable to import ' . ucfirst($sharer_type)];
            $status = ['type' => 'danger', 'message' => trans('common.unable_to_import_'.strtolower($sharer_type))];

        }

        return back()->with('status', $status);

    }

    private function getValidationRules($id = '')
    {

        $rules = [
            'customer_type' => 'required',
        ];

        if ($id) {
            $rules['name'] = 'required|unique_with:suppliers_or_customers,name, phone,company_id,member_id,' . $id;
        } else {
            $rules['name'] = 'required|unique_with:suppliers_or_customers,name, phone,company_id,member_id';
        }

        return $rules;
    }

    private function set_account_head(Request $request, $id = '')
    {
        $data = [];
        $data['display_name'] = $request->display_name;
        $data['name'] = snake_case($request->name);

        $this->validate($request, $this->accountTypeRules($id));

        if ($id) {
            $acc = AccountType::where('id', $id)->first();

            return $acc->update($data);
        } else
            return AccountType::create($data);

    }

    private function accountTypeRules($id = '')
    {
        if (is_null($id)) {
            $data = [
                'name' => 'required|unique_with:account_types,name, company_id, member_id',
                'display_name' => 'required|unique_with:account_types,display_name, name, company_id, member_id',
            ];
        } else {
            $data = [
                'name' => 'required|unique_with:account_types,name, company_id, member_id,' . $id,
                'display_name' => 'required|unique_with:account_types,display_name, name, company_id, member_id,' . $id,
            ];
        }

        return $data;
    }

    private function set_cash_or_bank(Request $request, $id = '')
    {
        $account_type = AccountType::where('display_name', $request->display_name)->first();

        $data = [];
        $data['title'] = $request['title'] = $request->display_name;
        $data['contact_person'] = $request['contact_person'] = $request->display_name;
        $data['phone'] = $request->phone;

        $this->validate($request, $this->cashBankAccountRules($id));

        $data['account_type_id'] = $account_type->id;

        if ($id) {
            $cash_banks = CashOrBankAccount::where('id', $id)->first();

            return $cash_banks->update($data);
        } else
            return CashOrBankAccount::create($data);

    }

    private function cashBankAccountRules($id = '')
    {
        $rules = [
            'contact_person' => 'required',
            'phone' => 'required'
        ];

        if ($id == '') {
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id';
        } else {
            $rules['title'] = 'required|unique_with:cash_or_bank_accounts,title,company_id,member_id,' . $id;
        }


        return $rules;
    }

    public function customerImportSample()
    {

        return response()->download(public_path('sample_excel/customer.xlsx'));
    }

    public function supplierImportSample()
    {

        return response()->download(public_path('sample_excel/supplier.xlsx'));
    }


}
