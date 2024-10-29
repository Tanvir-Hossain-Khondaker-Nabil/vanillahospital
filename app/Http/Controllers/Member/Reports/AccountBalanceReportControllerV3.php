<?php


namespace App\Http\Controllers\Member\Reports;

use App\Http\ECH\AccountFinalReportV3;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\SupplierOrCustomer;
use Illuminate\Http\Request;

class AccountBalanceReportControllerV3 extends BaseReportController
{
    protected $accountFinalReport;

    public function __construct()
    {
        $this->accountFinalReport = new AccountFinalReportV3();
    }

    private function set_from_to_date($request)
    {
        if (!$request->returnData) {
            $this->searchDate($request);
        } else {
            $this->fromDate = $request->fromDate;
            $this->toDate = $request->toDate;
            $this->pre_toDate = $request->pre_toDate;
            $this->pre_fromDate =  $request->pre_fromDate;
        }
    }

    public function create_trail_balance($value)
    {
        $result = [];
        $result['total_cr'] = $this->total_cr($value);
        $result['total_dr'] = $this->total_dr($value);

        return $result;
    }

    public function fixed_asset_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $fixed_asset = AccountType::where('name', '=', "fixed_assets")->select('id')->first();
        $fixed_assets = AccountType::where('parent_id', $fixed_asset->id)->select('id', 'display_name')->get();

         $fixed_asset_balance = $this->accountFinalReport->account_latest_balance($fixed_asset->id, $this->toDate, $request->company_id);
        $data['fixed_asset'] = $fixed_asset_balance ? $fixed_asset_balance[0] : '';

        $fixed_asset_pre_balance = $this->accountFinalReport->account_previous_balance($fixed_asset->id, $this->fromDate, $request->company_id);
        $data['pre_fixed_asset'] = $fixed_asset_pre_balance ? $fixed_asset_pre_balance[0] : '';

        $data['total_fixed'] = $fixed_asset_balance ? $fixed_asset_balance[0]->balance : 0;
        $data['pre_total_fixed'] = $fixed_asset_pre_balance ? $fixed_asset_pre_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['fixed_total_cr'] = 0;
            $data['fixed_total_dr'] = 0;
        }


        $data['fixed_assets'] = [];
        foreach ($fixed_assets as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['fixed_total_cr'] += $result['total_cr'];
                    $data['fixed_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('fixed_assets', $data, $value, $balance, $pre_balance, $result);

                $data['pre_total_fixed'] += $pre_balance;
                $data['total_fixed'] += $balance;

            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function expense_report(Request $request, $data = '')
    {
        $this->set_from_to_date($request);

        $expense = AccountType::where('name', '=', "expenses")->select('id')->first();
        $expenses = AccountType::where('parent_id', $expense->id)->select('id', 'display_name')->get();


        $ex_balance = $this->accountFinalReport->account_latest_balance($expense->id, $this->toDate, $request->company_id);
        $pre_ex_balance = $this->accountFinalReport->account_previous_balance($expense->id, $this->fromDate, $request->company_id);

        $data['ex_balance'] = $data['total_expenses'] = $ex_balance ? $ex_balance[0]->balance : 0;
        $data['pre_ex_balance'] = $data['pre_total_expenses'] = $pre_ex_balance ? $pre_ex_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['expense_total_cr'] = 0;
            $data['expense_total_dr'] = 0;
        }

        $data['expenses'] = [];

        foreach ($expenses as $key => $value) {
            $child_expenses = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();

            if (count($child_expenses) > 0) {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

                $data['expenses'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance-$pre_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['expense_total_cr'] += $result['total_cr'];
                    $data['expense_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('expenses', $data, $value, $balance, $pre_balance, $result);

                $data['total_expenses'] += $balance;
                $data['pre_total_expenses'] += $pre_balance;

                foreach ($child_expenses as $value2) {

                    $query2 = $this->accountFinalReport->account_latest_balance($value2->id, $this->toDate, $request->company_id);
                    $previous_query2 = $this->accountFinalReport->account_previous_balance($value2->id, $this->fromDate, $request->company_id);


                    if ($query2) {
                        $data['expenses'][$value2->id]['child'] = "yes";

                        $query2_balance = $query2 ? $query2[0]->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2[0]->balance : 0;
                        $balance = $query2_balance-$pre_balance;

                        if($request->trail_balance)
                        {
                            $result = $this->create_trail_balance($value2);
                            $data['expense_total_cr'] += $result['total_cr'];
                            $data['expense_total_dr'] += $result['total_dr'];
                        }

                        $data = $this->generate_account_final_report('expenses', $data, $value2, $balance, $pre_balance, $result);


                        $data['total_expenses'] += $balance;
                        $data['pre_total_expenses'] += $pre_balance;

                    }
                }


            } else {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

                if ($query) {

                    $query_balance = $query ? $query[0]->balance : 0;
                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance-$pre_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['expense_total_cr'] += $result['total_cr'];
                        $data['expense_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('expenses', $data, $value, $balance, $pre_balance, $result);

                    $data['total_expenses'] += $balance;
                    $data['pre_total_expenses'] += $pre_balance;
                }
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }
    }

    public function income_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $income = AccountType::where('name', '=', "other_revenue")->select('id', 'display_name')->first();
        $incomes = AccountType::where('parent_id', $income->id);
        $incomes = $incomes->authCompany($incomes, $request)->select('id', 'display_name')->get();


        $in_balance = $this->accountFinalReport->account_latest_balance($income->id, $this->toDate, $request->company_id);
        $pre_in_balance = $this->accountFinalReport->account_previous_balance($income->id, $this->fromDate, $request->company_id);

        $data['in_balance'] = $data['total_incomes'] = $in_balance ? (-1) * $in_balance[0]->balance : 0;
        $data['pre_total_incomes'] = $pre_in_balance ? (-1) * $pre_in_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['income_total_cr'] = 0;
            $data['income_total_dr'] = 0;
        }

        $data['incomes'] = [];

        foreach ($incomes as $key => $value) {
            $child_incomes = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();
            if (count($child_incomes) > 0) {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

                $data['incomes'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance - $pre_balance;


                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['income_total_cr'] += $result['total_cr'];
                    $data['income_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('incomes', $data, $value, $balance, $pre_balance, $result, '-');

                $data['total_incomes'] += (-1)*$balance;
                $data['pre_total_incomes'] += (-1)*$pre_balance;

                foreach ($child_incomes as $value2) {

                    $query2 = $this->accountFinalReport->account_latest_balance($value2->id, $this->toDate, $request->company_id);
                    $previous_query2 = $this->accountFinalReport->account_previous_balance($value2->id, $this->fromDate, $request->company_id);


                    if ($query2) {
                        $data['incomes'][$value2->id]['child'] = "yes";

                        $query2_balance = $query2 ? $query2[0]->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2[0]->balance : 0;
                        $balance = $query2_balance - $pre_balance;


                        if($request->trail_balance)
                        {
                            $result = $this->create_trail_balance($value2);
                            $data['income_total_cr'] += $result['total_cr'];
                            $data['income_total_dr'] += $result['total_dr'];
                        }

                        $data = $this->generate_account_final_report('incomes', $data, $value2, $balance, $pre_balance, $result, '-');

                        $data['total_incomes'] += (-1)*$balance;
                        $data['pre_total_incomes'] += (-1)*$pre_balance;
                    }
                }
            } else {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


                if ($query) {
                    $query_balance = $query ? $query[0]->balance : 0;
                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance - $pre_balance;


                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['income_total_cr'] += $result['total_cr'];
                        $data['income_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('incomes', $data, $value, $balance, $pre_balance, $result, '-');

                    $data['total_incomes'] += (-1)*$balance;
                    $data['pre_total_incomes'] += (-1)*$pre_balance;

                }
            }
        }


        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function current_asset_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $current_asset = AccountType::where('name', '=', "current_assets")->select('id')->first();
        $current_assets = AccountType::whereNotIn('name', ['cash', 'bank', 'advance_deposits&_prepayments', 'due_from_affiliated_company', 'fixed_deposits_receipts'])->where('parent_id', $current_asset->id)->select('id', 'display_name')->get();

        $data_current_asset = $this->accountFinalReport->account_latest_balance($current_asset->id, $this->toDate, $request->company_id);
        $pre_data_current_asset = $this->accountFinalReport->account_previous_balance($current_asset->id, $this->fromDate, $request->company_id);

        $data['current_asset_balance'] = $data['total_current_asset'] = $data_current_asset ? $data_current_asset[0]->balance : 0;
        $data['pre_current_asset_balance'] = $data['pre_total_current_asset'] = $pre_data_current_asset ? $pre_data_current_asset[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['current_assets_total_cr'] = 0;
            $data['current_assets_total_dr'] = 0;
        }

        $data['current_assets'] = [];

        foreach ($current_assets as $key => $value) {

            $child_current_assets = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();

            if (count($child_current_assets) > 0) {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


                $data['current_assets'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;


                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['current_assets_total_cr'] += $result['total_cr'];
                    $data['current_assets_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('current_assets', $data, $value, $balance, $pre_balance, $result);

                $data['total_current_asset'] += $balance;
                $data['pre_total_current_asset'] += $pre_balance;

                foreach ($child_current_assets as $value2) {

                    $query2 = $this->accountFinalReport->account_latest_balance($value2->id, $this->toDate, $request->company_id);
                    $previous_query2 = $this->accountFinalReport->account_previous_balance($value2->id, $this->fromDate, $request->company_id);

                    if ($query2) {
                        $data['current_assets'][$value2->id]['child'] = "yes";
                        $query2_balance = $query2 ? $query2[0]->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2[0]->balance : 0;
                        $balance = $query2_balance;

                        if($request->trail_balance)
                        {
                            $result = $this->create_trail_balance($value2);
                            $data['current_assets_total_cr'] += $result['total_cr'];
                            $data['current_assets_total_dr'] += $result['total_dr'];
                        }

                        $data = $this->generate_account_final_report('current_assets', $data, $value2, $balance, $pre_balance, $result);


                        $data['total_current_asset'] += $balance;
                        $data['pre_total_current_asset'] += $pre_balance;
                    }
                }

            } else {

                $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
                $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

                if ($query) {
                    $query_balance = $query ? $query[0]->balance : 0;
                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['current_assets_total_cr'] += $result['total_cr'];
                        $data['current_assets_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('current_assets', $data, $value, $balance, $pre_balance, $result);

                    $data['total_current_asset'] += $balance;
                    $data['pre_total_current_asset'] += $pre_balance;
                }
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function advance_prepayment_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $advance_prepayment = AccountType::where('name', '=', "advance_deposits&_prepayments")->select('id')->first();
        $advance_prepayments = AccountType::where('parent_id', $advance_prepayment->id)->select('id', 'display_name')->get();

        $data_advance_prepayment_balance = $this->accountFinalReport->account_latest_balance($advance_prepayment->id, $this->toDate, $request->company_id);
        $pre_data_advance_prepayment_balance = $this->accountFinalReport->account_previous_balance($advance_prepayment->id, $this->fromDate, $request->company_id);

        $data['total_advance_prepayment'] = $data_advance_prepayment_balance ? $data_advance_prepayment_balance[0]->balance : 0;
        $data['pre_total_advance_prepayment'] = $pre_data_advance_prepayment_balance ? $pre_data_advance_prepayment_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['advance_prepayments_total_cr'] = 0;
            $data['advance_prepayments_total_dr'] = 0;
        }

        $data['advance_prepayments'] = [];
        foreach ($advance_prepayments as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['advance_prepayments_total_cr'] += $result['total_cr'];
                    $data['advance_prepayments_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('advance_prepayments', $data, $value, $balance, $pre_balance, $result);


                $data['total_advance_prepayment'] += $balance;
                $data['pre_total_advance_prepayment'] += $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function fixed_deposits_receipts_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $fixed_deposits_receipt = AccountType::where('name', '=', "fixed_deposits_receipts")->select('id')->first();
        $fixed_deposits_receipts = AccountType::where('parent_id', $fixed_deposits_receipt->id)->select('id', 'display_name')->get();

        $fixed_deposits_receipt_balance = $this->accountFinalReport->account_latest_balance($fixed_deposits_receipt->id, $this->toDate, $request->company_id);
        $pre_fixed_deposits_receipt_balance = $this->accountFinalReport->account_previous_balance($fixed_deposits_receipt->id, $this->fromDate, $request->company_id);


        $data['total_fixed_deposits_receipt'] = $fixed_deposits_receipt_balance ? $fixed_deposits_receipt_balance[0]->balance : 0;
        $data['pre_total_fixed_deposits_receipt'] = $pre_fixed_deposits_receipt_balance ? $pre_fixed_deposits_receipt_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['fixed_deposits_receipts_total_cr'] = 0;
            $data['fixed_deposits_receipts_total_dr'] = 0;
        }

        $data['fixed_deposits_receipts'] = [];
        foreach ($fixed_deposits_receipts as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['fixed_deposits_receipts_total_cr'] += $result['total_cr'];
                    $data['fixed_deposits_receipts_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('fixed_deposits_receipts', $data, $value, $balance, $pre_balance, $result);

                $data['total_fixed_deposits_receipt'] += $balance;
                $data['pre_total_fixed_deposits_receipt'] += $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function due_companies_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $due_from_affiliated_company = AccountType::where('name', '=', "due_from_affiliated_company")->select('id')->first();
        $due_from_affiliated_companies = AccountType::where('parent_id', $due_from_affiliated_company->id)->select('id', 'display_name')->get();

        $data_due_from_affiliated_company_balance = $this->accountFinalReport->account_latest_balance($due_from_affiliated_company->id, $this->toDate, $request->company_id);
        $pre_due_from_affiliated_company_balance = $this->accountFinalReport->account_previous_balance($due_from_affiliated_company->id, $this->fromDate, $request->company_id);

        $data['total_due_affiliated_company'] = $data_due_from_affiliated_company_balance ? $data_due_from_affiliated_company_balance[0]->balance : 0;
        $data['pre_total_due_affiliated_company'] = $pre_due_from_affiliated_company_balance ? $pre_due_from_affiliated_company_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['due_companies_total_cr'] = 0;
            $data['due_companies_total_dr'] = 0;
        }

        $data['due_companies'] = [];
        foreach ($due_from_affiliated_companies as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['due_companies_total_cr'] += $result['total_cr'];
                    $data['due_companies_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('due_companies', $data, $value, $balance, $pre_balance, $result);



                $data['total_due_affiliated_company'] += $balance;
                $data['pre_total_due_affiliated_company'] += $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function cash_bank_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['total_cash_bank'] = 0;
        $data['pre_total_cash_bank'] = 0;
        $data['cash_banks'] = [];

        if($request->trail_balance)
        {
            $data['cash_banks_total_cr'] = 0;
            $data['cash_banks_total_dr'] = 0;
        }

        $cash_bank_account = AccountType::authCompany()->whereIn('name', ['cash', 'bank'])->pluck('id')->toArray();
        $cash_banks = AccountType::whereIn('id', [3, 4])->orwhereIntegerInRaw('parent_id', $cash_bank_account);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->select('id', 'display_name')->orderBy('id', 'asc')->get();


        foreach ($cash_banks as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;

                if ($query_balance != 0) {

                    $query_balance = $query ? $query[0]->balance : 0;
                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['cash_banks_total_cr'] += $result['total_cr'];
                        $data['cash_banks_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('cash_banks', $data, $value, $balance, $pre_balance, $result);

                    $data['total_cash_bank'] += $balance;
                    $data['pre_total_cash_bank'] += $pre_balance;
                }
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function bank_overdraft_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['total_over_bank'] = 0;
        $data['pre_total_over_bank'] = 0;
        $data['over_banks'] = [];

        if($request->trail_balance)
        {
            $data['over_banks_total_cr'] = 0;
            $data['over_banks_total_dr'] = 0;
        }

        $over_bank_account = AccountType::authCompany()->where('name', 'current_liabilities')->pluck('id')->toArray();
        $over_bank = AccountType::whereIntegerInRaw('parent_id', $over_bank_account)->pluck('id')->toArray();
        $over_banks = CashOrBankAccount::whereIntegerInRaw('account_type_id', $over_bank)->pluck('account_type_id')->toArray();

        $over_banks = AccountType::whereIntegerInRaw('id', $over_banks);
        $over_banks = $this->authCompany($over_banks, $request);
        $over_banks = $over_banks->select('id', 'display_name')->orderBy('id', 'asc')->get();


        foreach ($over_banks as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;

                if ($query_balance != 0) {

                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['over_banks_total_cr'] += $result['total_cr'];
                        $data['over_banks_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('over_banks', $data, $value, $balance, $pre_balance, $result, '-');

                    $data['total_over_bank'] += (-1) * $balance;
                    $data['pre_total_over_bank'] += (-1) * $pre_balance;
                }
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function trade_debtor_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        // Only Customer are Trade Debtors
        // who have Debit balance must be bigger than 0;
        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $customers = $this->authCompany($supplier_or_customers, $request);
        $customerAccountTypes = $customers->where('customer_type', 'customer')->get()->pluck('account_type_id')->toArray();

        $customers = AccountType::whereIntegerInRaw('id', $customerAccountTypes);
        $customers = $customers->select('id', 'display_name')->orderBy('id', 'asc')->get();

        if($request->trail_balance)
        {
            $data['trade_debtors_total_cr'] = 0;
            $data['trade_debtors_total_dr'] = 0;
        }

        $data['trade_debtors'] = [];
        $data['total_trade_debtor'] = 0;
        $data['pre_total_trade_debtor'] = 0;

        foreach ($customers as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                // Note: Change on Request Azim company
                $query_balance = $query ? $query[0]->balance : 0;

                if ($query_balance != 0) {

                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['trade_debtors_total_cr'] += $result['total_cr'];
                        $data['trade_debtors_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('trade_debtors', $data, $value, $balance, $pre_balance, $result);


                    $data['total_trade_debtor'] += $balance;
                    $data['pre_total_trade_debtor'] += $pre_balance;

                }
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function sundry_creditor_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['sundry_creditors'] = [];
        $data['total_sundry_creditors'] = 0;
        $data['pre_total_sundry_creditors'] = 0;

        if($request->trail_balance)
        {
            $data['sundry_creditors_total_cr'] = 0;
            $data['sundry_creditors_total_dr'] = 0;
        }

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $suppliers = $this->authCompany($supplier_or_customers, $request);
        $sundrys = $suppliers->onlySuppliers()->get()->pluck('account_type_id')->toArray();

        $sundry_creditors = AccountType::whereIntegerInRaw('id', $sundrys);
        $sundry_creditors = $sundry_creditors->select('id', 'display_name')->orderBy('id', 'asc')->get();

        foreach ($sundry_creditors as $key => $value) {


            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                // Note: Change on Request Azim company
                $query_balance = $query ? $query[0]->balance : 0;

                if ($query_balance != 0) {
                    $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                    $balance = $query_balance;

                    if($request->trail_balance)
                    {
                        $result = $this->create_trail_balance($value);
                        $data['sundry_creditors_total_cr'] += $result['total_cr'];
                        $data['sundry_creditors_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('sundry_creditors', $data, $value, $balance, $pre_balance, $result, '-');

                    $data['total_sundry_creditors'] += (-1) * $balance;
                    $data['pre_total_sundry_creditors'] += (-1) * $pre_balance;
                }
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }


    }

    public function account_payable_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['account_payables'] = [];
        $data['total_account_payables'] = 0;
        $data['pre_total_account_payables'] = 0;

        if($request->trail_balance)
        {
            $data['account_payables_total_cr'] = 0;
            $data['account_payables_total_dr'] = 0;
        }

        $accounts_payable = AccountType::where('name', '=', "accounts_payable")->select('id')->first();
        $accounts_payables = AccountType::where('parent_id', $accounts_payable->id)->select('id', 'display_name')->get();

        foreach ($accounts_payables as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['account_payables_total_cr'] += $result['total_cr'];
                    $data['account_payables_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('account_payables', $data, $value, $balance, $pre_balance, $result, '-');

                $data['total_account_payables'] += (-1) * $balance;
                $data['pre_total_account_payables'] += (-1) * $pre_balance;
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }


    }

    public function account_receivable_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['account_receivables'] = [];
        $data['total_account_receivables'] = 0;
        $data['pre_total_account_receivables'] = 0;

        if($request->trail_balance)
        {
            $data['account_receivables_total_cr'] = 0;
            $data['account_receivables_total_dr'] = 0;
        }

        $accounts_receivable = AccountType::where('name', '=', "accounts_receivable")->select('id')->first();
        $accounts_receivables = AccountType::where('parent_id', $accounts_receivable->id)->select('id', 'display_name')->get();

        foreach ($accounts_receivables as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['account_receivables_total_cr'] += $result['total_cr'];
                    $data['account_receivables_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('account_receivables', $data, $value, $balance, $pre_balance, $result);

                $data['total_account_receivables'] +=  $balance;
                $data['pre_total_account_receivables'] +=  $pre_balance;

            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }


    }

    public function equity_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $equity = AccountType::where('name', '=', "equity")->select('id')->first();
        $equities = AccountType::where('parent_id', $equity->id)->select('id', 'display_name')->get();


        $data_equity = $this->accountFinalReport->account_latest_balance($equity->id, $this->toDate, $request->company_id);
        $pre_equity = $this->accountFinalReport->account_previous_balance($equity->id, $this->fromDate, $request->company_id);

        $data['equity_balance'] = $data_equity ? $data_equity[0]->balance : 0;
        $data['pre_equity_balance'] = $pre_equity ? $pre_equity[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['equities_total_cr'] = 0;
            $data['equities_total_dr'] = 0;
        }

        $data['equities'] = [];
        foreach ($equities as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['equities_total_cr'] += $result['total_cr'];
                    $data['equities_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('equities', $data, $value, $balance, $pre_balance, $result);

                $data['equity_balance'] += $balance;
                $data['pre_equity_balance'] += $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }


    }

    public function long_term_liability_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $non_current_liability = AccountType::where('name', '=', "long-term_liabilities")->select('id')->first();
        $non_current_liabilities = AccountType::where('parent_id', $non_current_liability->id)->select('id', 'display_name')->get();


        $data_non_current_liability = $this->accountFinalReport->account_latest_balance($non_current_liability->id, $this->toDate, $request->company_id);
        $pre_non_current_liability = $this->accountFinalReport->account_previous_balance($non_current_liability->id, $this->fromDate, $request->company_id);

        $data['non_current_liability_balance'] = $data['total_non_current_liability'] = $data_non_current_liability ? (-1) * $data_non_current_liability[0]->balance : 0;
        $data['pre_non_current_liability_balance'] = $data['pre_total_non_current_liability'] = $pre_non_current_liability ? (-1) * $pre_non_current_liability[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['non_current_liabilities_total_cr'] = 0;
            $data['non_current_liabilities_total_dr'] = 0;
        }

        $data['non_current_liabilities'] = [];
        foreach ($non_current_liabilities as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['non_current_liabilities_total_cr'] += $result['total_cr'];
                    $data['non_current_liabilities_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('non_current_liabilities', $data, $value, $balance, $pre_balance, $result, '-');

                $data['total_non_current_liability'] += (-1) * $balance;
                $data['pre_total_non_current_liability'] += (-1) * $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function current_liability_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $current_liability = AccountType::where('name', '=', "current_liabilities")->select('id')->first();
        $current_liabilities = AccountType::whereNotIn('name', ['due_to_affiliated_company', 'liabilities_for_expenses', 'income_tax_payble'])->where('parent_id', $current_liability->id)->select('id', 'display_name')->get();


        $data_current_liability = $this->accountFinalReport->account_latest_balance($current_liability->id, $this->toDate, $request->company_id);
        $pre_current_liability = $this->accountFinalReport->account_previous_balance($current_liability->id, $this->fromDate, $request->company_id);

        $data['current_liability_balance'] = $data['total_current_liability'] = $data_current_liability ? (-1) * $data_current_liability[0]->balance : 0;
        $data['pre_current_liability_balance'] = $data['pre_total_current_liability'] = $pre_current_liability ? (-1) * $pre_current_liability[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['current_liabilities_total_cr'] = 0;
            $data['current_liabilities_total_dr'] = 0;
        }

        $data['current_liabilities'] = [];
        foreach ($current_liabilities as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);

            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['current_liabilities_total_cr'] += $result['total_cr'];
                    $data['current_liabilities_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('current_liabilities', $data, $value, $balance, $pre_balance, $result, '-');


                $data['total_current_liability'] += (-1) * $balance;
                $data['pre_total_current_liability'] += (-1) * $pre_balance;
            }
        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function due_to_affiliated_company_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['total_due_affiliated'] = 0;
        $data['due_to_affiliated_companies'] = [];

        if($request->trail_balance)
        {
            $data['due_to_affiliated_companies_total_cr'] = 0;
            $data['due_to_affiliated_companies_total_dr'] = 0;
        }

        $due_to_affiliated_company = AccountType::where('name', '=', "due_to_affiliated_company")->select('id')->first();
        $due_to_affiliated_companies = AccountType::where('parent_id', $due_to_affiliated_company->id)->select('id', 'display_name')->get();


        $data_due_to_affiliated_company = $this->accountFinalReport->account_latest_balance($due_to_affiliated_company->id, $this->toDate, $request->company_id);
        $pre_due_to_affiliated_company = $this->accountFinalReport->account_previous_balance($due_to_affiliated_company->id, $this->fromDate, $request->company_id);

        $data['due_to_affiliated_company_balance'] = $data['total_due_affiliated'] = $data_due_to_affiliated_company ? (-1) * $data_due_to_affiliated_company[0]->balance : 0;
        $data['pre_due_to_affiliated_company_balance'] = $data['pre_total_due_affiliated'] = $pre_due_to_affiliated_company ? (-1) * $pre_due_to_affiliated_company[0]->balance : 0;

        foreach ($due_to_affiliated_companies as $key => $value) {


            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['due_to_affiliated_companies_total_cr'] += $result['total_cr'];
                    $data['due_to_affiliated_companies_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('due_to_affiliated_companies', $data, $value, $balance, $pre_balance, $result, '-');


                $data['total_due_affiliated'] += (-1) * $balance;
                $data['pre_total_due_affiliated'] += (-1) * $pre_balance;
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function liability_for_expense_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['total_liabilities_expenses'] = 0;

        if($request->trail_balance)
        {
            $data['liabilities_expenses_total_cr'] = 0;
            $data['liabilities_expenses_total_dr'] = 0;
        }

        $liabilities_expense = AccountType::where('name', '=', "liabilities_for_expenses")->select('id')->first();


        $data_liabilities_expense = $this->accountFinalReport->account_latest_balance($liabilities_expense->id, $this->toDate, $request->company_id);
        $pre_liabilities_expense = $this->accountFinalReport->account_previous_balance($liabilities_expense->id, $this->fromDate, $request->company_id);


        $data['liabilities_expenses_balance'] = $data['total_liabilities_expenses'] = $data_liabilities_expense ? (-1) * $data_liabilities_expense[0]->balance : 0;
        $data['pre_liabilities_expenses_balance'] = $data['pre_total_liabilities_expenses'] = $pre_liabilities_expense ? (-1) * $pre_liabilities_expense[0]->balance : 0;

        $liabilities_expenses = AccountType::where('parent_id', $liabilities_expense->id)->select('id', 'display_name')->get();

        $data['liabilities_expenses'] = [];
        foreach ($liabilities_expenses as $key => $value) {


            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);



            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['liabilities_expenses_total_cr'] += $result['total_cr'];
                    $data['liabilities_expenses_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('liabilities_expenses', $data, $value, $balance, $pre_balance, $result, '-');


                $data['total_liabilities_expenses'] += (-1) * $balance;
                $data['pre_total_liabilities_expenses'] += (-1) * $pre_balance;
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }

    }

    public function income_tax_payable_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $data['total_income_tax_payable'] = 0;

        if($request->trail_balance)
        {
            $data['income_tax_payables_total_cr'] = 0;
            $data['income_tax_payables_total_dr'] = 0;
        }

        $income_tax_payable = AccountType::where('name', '=', "income_tax_payble")->select('id')->first();
        $income_tax_payables = AccountType::where('parent_id', $income_tax_payable->id)->select('id', 'display_name')->get();

        $data_income_tax_payable = $this->accountFinalReport->account_latest_balance($income_tax_payable->id, $this->toDate, $request->company_id);
        $pre_income_tax_payable = $this->accountFinalReport->account_previous_balance($income_tax_payable->id, $this->fromDate, $request->company_id);


        $data['income_tax_payable_balance'] = $data['total_income_tax_payable'] = $data_income_tax_payable ? (-1) * $data_income_tax_payable[0]->balance : 0;
        $data['pre_income_tax_payable_balance'] = $data['pre_total_income_tax_payable'] = $pre_income_tax_payable ? (-1) * $pre_income_tax_payable[0]->balance : 0;

        $data['income_tax_payables'] = [];
        foreach ($income_tax_payables as $key => $value) {


            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


            if ($query) {
                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['income_tax_payables_total_cr'] += $result['total_cr'];
                    $data['income_tax_payables_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('income_tax_payables', $data, $value, $balance, $pre_balance, $result, '-');


                $data['total_income_tax_payable'] += (-1) * $balance;
                $data['pre_total_income_tax_payable'] += (-1) * $pre_balance;
            }

        }

        if ($request->returnData) {
            return $data;
        } else {

        }


    }

    public function cost_of_sold_report(Request $request, $data = '')
    {
        $this->set_from_to_date($request);

        $cost_of_sold = AccountType::where('name', '=', "cost_of_goods_sold")->select('id')->first();
        $cost_of_sold_items = AccountType::where('parent_id', $cost_of_sold->id)->select('id', 'display_name')->get();

        $cost_of_sold_balance = $this->accountFinalReport->account_latest_balance($cost_of_sold->id, $this->toDate, $request->company_id);
        $pre_cost_of_sold_balance = $this->accountFinalReport->account_previous_balance($cost_of_sold->id, $this->fromDate, $request->company_id);

        $data['cost_of_sold_balance'] = $cost_of_sold_balance ? $cost_of_sold_balance[0]->balance : 0;
        $data['pre_cost_of_sold_balance'] = $pre_cost_of_sold_balance ? $pre_cost_of_sold_balance[0]->balance : 0;

        if($request->trail_balance)
        {
            $data['cost_of_sold_items_total_cr'] = 0;
            $data['cost_of_sold_items_total_dr'] = 0;
        }


        $total_cost_of_sold = $data['openingStock'] + $data['total_purchases'] - $data['total_inventory'] + $data['cost_of_sold_balance'];
        $pre_total_cost_of_sold = $data['pre_openingStock'] + $data['pre_total_purchases'] - $data['pre_total_inventory'] + $data['pre_cost_of_sold_balance'];
        $data['cost_of_sold_items'] = [];

        foreach ($cost_of_sold_items as $key => $value) {

            $query = $this->accountFinalReport->account_latest_balance($value->id, $this->toDate, $request->company_id);
            $previous_query = $this->accountFinalReport->account_previous_balance($value->id, $this->fromDate, $request->company_id);


            if ($query) {

                $query_balance = $query ? $query[0]->balance : 0;
                $pre_balance = $previous_query ? $previous_query[0]->balance : 0;
                $balance = $query_balance;

                if($request->trail_balance)
                {
                    $result = $this->create_trail_balance($value);
                    $data['cost_of_sold_items_total_cr'] += $result['total_cr'];
                    $data['cost_of_sold_items_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('cost_of_sold_items', $data, $value, $balance, $pre_balance, $result);


                $total_cost_of_sold += $balance;
                $pre_total_cost_of_sold += $pre_balance;
            }
        }
        $data['total_cost_of_sold'] = $total_cost_of_sold;
        $data['pre_total_cost_of_sold'] = $pre_total_cost_of_sold;


        if ($request->returnData) {
            return $data;
        } else {

        }

    }


    protected function total_dr($value)
    {
        $total_dr = $value->transaction_details()
            ->where('date', '>=', $this->fromDate)
            ->where('date', '<=', $this->toDate)
            ->where('transaction_type', 'dr')
            ->sum('amount');

        return $total_dr;

    }

    protected function total_cr($value)
    {
        $total_cr = $value->transaction_details()
            ->where('date', '>=', $this->fromDate)
            ->where('date', '<=', $this->toDate)
            ->where('transaction_type', 'cr')
            ->sum('amount');

        return $total_cr;

    }

    public function generate_account_final_report($key_name, $data, $value, $balance, $pre_balance, $result, $balanceOperator = "+"){

        $this->accountFinalReport->setValue($key_name, $value, $balanceOperator);
        $data = $this->accountFinalReport->getAccountBalance($data, $balance);
        $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

        if($data['trail_balance'])
            $data = $this->accountFinalReport->getAccountDrCrBalance($data, $result);

        return $data;

    }



}
