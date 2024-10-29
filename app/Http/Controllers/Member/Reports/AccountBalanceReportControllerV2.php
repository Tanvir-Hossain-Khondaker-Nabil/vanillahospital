<?php


namespace App\Http\Controllers\Member\Reports;

use App\Http\ECH\AccountFinalReport;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\ProfitBalanceTrack;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountBalanceReportControllerV2 extends BaseReportController
{
    protected $accountFinalReport;
    public $total_dr;
    public $total_cr;

    public function __construct()
    {
        $this->accountFinalReport = new AccountFinalReport();
        $this->total_dr = 0;
        $this->total_cr = 0;
    }

    private function set_from_to_date($request)
    {
        if (!$request->returnData) {
            $this->searchDate($request);
        } else {
            $this->fromDate = $request->fromDate;
            $this->toDate = $request->toDate;
            $this->pre_toDate = $request->pre_toDate;
            $this->pre_fromDate = $request->pre_fromDate;
        }
    }

    public function create_trail_balance($value, $company_id)
    {

        $result['total_cr'] = $result['total_dr'] = 0;

        $total = $this->total_dr_cr($value, $company_id);
        foreach ($total as $val)
        {
            if($val->transaction_type == "dr")
            {
                $result['total_dr'] = $val->tr_amount;
                $this->total_dr += $val->tr_amount;
            }else{
                $result['total_cr'] = $val->tr_amount;
                $this->total_cr += $val->tr_amount;
            }
        }

        return $result;
    }

    public function fixed_asset_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $fixed_asset = AccountType::where('name', '=', "fixed_assets")->select('id')->first();
        $fixed_assets = AccountType::where('id', $fixed_asset->id)->orwhere('parent_id', $fixed_asset->id)->authCompany()->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        if(empty($data))
            $data = [];

        $data['total_fixed'] = 0;
        $data['pre_total_fixed'] = 0;


        if ($request->trail_balance) {
            $data['fixed_total_cr'] = 0;
            $data['fixed_total_dr'] = 0;
        }

        $data['fixed_assets'] = [];
        foreach ($fixed_assets as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['fixed_total_cr'] += $result['total_cr'];
                $data['fixed_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('fixed_assets', $data, $value, $balance, $pre_balance, $result);

            $data['pre_total_fixed'] += $pre_balance;
            $data['total_fixed'] += $balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Fixed Assets <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-fixed-assets', $data);
        }


    }

    public function expense_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $expenses = AccountType::where('name', '=', "expenses")->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->first();

        $data['total_expenses'] = 0;
        $data['pre_total_expenses'] = 0;

        if ($request->trail_balance) {
            $data['expense_total_cr'] = 0;
            $data['expense_total_dr'] = 0;
        }

        $data['expenses'] = [];

        $query = $this->account_latest_balance($expenses, $request);
        $previous_query = $this->account_previous_balance($expenses, $request);
        $pre_query = $this->account_pre_latest_balance($expenses, $request);

        $data['expenses'][$expenses->id]['parent'] = "yes";

        $query_balance = $query ? $query->balance : 0;
        $pre_balance = $previous_query ? $previous_query->balance : 0;
        $balance = $query_balance - $pre_balance;
        $pre_last_balance = $pre_query ? $pre_query->balance : 0;
        $pre_balance = $pre_balance - $pre_last_balance;

        $result = [];
        if ($request->trail_balance) {
            $result = $this->create_trail_balance($expenses, $request->company_id);

            $data['expense_total_cr'] += $result['total_cr'];
            $data['expense_total_dr'] += $result['total_dr'];
        }

        $data = $this->generate_account_final_report('expenses', $data, $expenses, $balance, $pre_balance, $result);

        $data['total_expenses'] += $balance;
        $data['pre_total_expenses'] += $pre_balance;

        $expenses = AccountType::where('parent_id', $expenses->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($expenses as $key => $value) {
            $child_expenses = AccountType::where('parent_id', $value->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


            if (count($child_expenses) > 0) {

                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);
                $pre_query = $this->account_pre_latest_balance($value, $request);

                $data['expenses'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance - $pre_balance;
                $pre_last_balance = $pre_query ? $pre_query->balance : 0;
                $pre_balance = $pre_balance - $pre_last_balance;

                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);

                    $data['expense_total_cr'] += $result['total_cr'];
                    $data['expense_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('expenses', $data, $value, $balance, $pre_balance, $result);

                $data['total_expenses'] += $balance;
                $data['pre_total_expenses'] += $pre_balance;

                foreach ($child_expenses as $value2) {

                    $query2 = $this->account_latest_balance($value2, $request);
                    $previous_query2 = $this->account_previous_balance($value2, $request);
                    $pre_query2 = $this->account_pre_latest_balance($value2, $request);

                    $data['expenses'][$value2->id]['child'] = "yes";

                    $query2_balance = $query2 ? $query2->balance : 0;
                    $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                    $balance = $query2_balance - $pre_balance;
                    $pre_last_balance = $pre_query2 ? $pre_query2->balance : 0;
                    $pre_balance = $pre_balance - $pre_last_balance;

                    $result = [];
                    if ($request->trail_balance) {
                        $result = $this->create_trail_balance($value2, $request->company_id);
                        $data['expense_total_cr'] += $result['total_cr'];
                        $data['expense_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('expenses', $data, $value2, $balance, $pre_balance, $result);


                    $data['total_expenses'] += $balance;
                    $data['pre_total_expenses'] += $pre_balance;

                }


            } else {
                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);
                $pre_query = $this->account_pre_latest_balance($value, $request);


                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance - $pre_balance;
                $pre_last_balance = $pre_query ? $pre_query->balance : 0;
                $pre_balance = $pre_balance - $pre_last_balance;

                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);
                    $data['expense_total_cr'] += $result['total_cr'];
                    $data['expense_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('expenses', $data, $value, $balance, $pre_balance, $result);

                $data['total_expenses'] += $balance;
                $data['pre_total_expenses'] += $pre_balance;

            }

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Expenses  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-expenses', $data);
        }
    }

    public function income_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $incomes = AccountType::where('name', '=', "other_revenue")->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->first();

        $data['total_incomes'] = 0;
        $data['pre_total_incomes'] = 0;


        if ($request->trail_balance) {
            $data['income_total_cr'] = 0;
            $data['income_total_dr'] = 0;
        }

        $data['incomes'] = [];

        $query = $this->account_latest_balance($incomes, $request);
        $previous_query = $this->account_previous_balance($incomes, $request);
        $pre_query = $this->account_pre_latest_balance($incomes, $request);

        $data['incomes'][$incomes->id]['parent'] = "yes";

        $query_balance = $query ? $query->balance : 0;
        $pre_balance = $previous_query ? $previous_query->balance : 0;
        $balance = $query_balance - $pre_balance;
        $pre_last_balance = $pre_query ? $pre_query->balance : 0;
        $pre_balance = $pre_balance - $pre_last_balance;


        $result = [];
        if ($request->trail_balance) {
            $result = $this->create_trail_balance($incomes, $request->company_id);
            $data['income_total_cr'] += $result['total_cr'];
            $data['income_total_dr'] += $result['total_dr'];
        }

        $data = $this->generate_account_final_report('incomes', $data, $incomes, $balance, $pre_balance, $result, '-');

        $data['total_incomes'] += (-1) * $balance;
        $data['pre_total_incomes'] += (-1) * $pre_balance;

        $incomes = AccountType::where('parent_id', $incomes->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        foreach ($incomes as $key => $value) {
            $child_incomes = AccountType::where('parent_id', $value->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();
            if (count($child_incomes) > 0) {

                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);
                $pre_query = $this->account_pre_latest_balance($value, $request);

                $data['incomes'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance - $pre_balance;
                $pre_last_balance = $pre_query ? $pre_query->balance : 0;
                $pre_balance = $pre_balance - $pre_last_balance;


                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);
                    $data['income_total_cr'] += $result['total_cr'];
                    $data['income_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('incomes', $data, $value, $balance, $pre_balance, $result, '-');

                $data['total_incomes'] += (-1) * $balance;
                $data['pre_total_incomes'] += (-1) * $pre_balance;

                foreach ($child_incomes as $value2) {

                    $query2 = $this->account_latest_balance($value2, $request);
                    $previous_query2 = $this->account_previous_balance($value2, $request);
                    $pre_query2 = $this->account_pre_latest_balance($value2, $request);


                    $data['incomes'][$value2->id]['child'] = "yes";

                    $query2_balance = $query2 ? $query2->balance : 0;
                    $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                    $balance = $query2_balance - $pre_balance;
                    $pre_last_balance = $pre_query2 ? $pre_query2->balance : 0;
                    $pre_balance = $pre_balance - $pre_last_balance;


                    $result = [];
                    if ($request->trail_balance) {
                        $result = $this->create_trail_balance($value2, $request->company_id);
                        $data['income_total_cr'] += $result['total_cr'];
                        $data['income_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('incomes', $data, $value2, $balance, $pre_balance, $result, '-');

                    $data['total_incomes'] += (-1) * $balance;
                    $data['pre_total_incomes'] += (-1) * $pre_balance;

                }
            } else {

                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);
                $pre_query = $this->account_pre_latest_balance($value, $request);


                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance - $pre_balance;
                $pre_last_balance = $pre_query ? $pre_query->balance : 0;
                $pre_balance = $pre_balance - $pre_last_balance;


                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);
                    $data['income_total_cr'] += $result['total_cr'];
                    $data['income_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('incomes', $data, $value, $balance, $pre_balance, $result, '-');

                $data['total_incomes'] += (-1) * $balance;
                $data['pre_total_incomes'] += (-1) * $pre_balance;


            }
        }


        if ($request->returnData) {
            return $data;
        } else {
            $data['report_title'] = "Incomes  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-incomes', $data);
        }

    }

    public function current_asset_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $current_asset = AccountType::where('name', '=', "current_assets")->select('id')->first();

        $not_current_assets = AccountType::whereIn('name', ['cash', 'bank', 'advance_deposits&_prepayments', 'due_from_affiliated_company', 'fixed_deposits_receipts', 'accounts_receivable'])->pluck('id')->toArray();

        $current_assets = AccountType::whereIntegerNotInRaw('id', $not_current_assets)
            ->where(function ($query) use ($current_asset) {
                $query->where('id', $current_asset->id)
                    ->orwhere('parent_id', $current_asset->id);
            })->authCompany()
            ->select('id', 'display_name')
            ->orderBy('display_name', 'asc')->get();

        $data['total_current_asset'] = 0;
        $data['pre_total_current_asset'] = 0;


        if ($request->trail_balance) {
            $data['current_assets_total_cr'] = 0;
            $data['current_assets_total_dr'] = 0;
        }

        $data['current_assets'] = [];
        foreach ($current_assets as $key => $value) {

            $child_current_assets = AccountType::whereIntegerNotInRaw('id', $not_current_assets)->where('parent_id', $value->id)->where('parent_id', '!=',$current_asset->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

            if (count($child_current_assets) > 0) {

                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);


                $data['current_assets'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;


                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);
                    $data['current_assets_total_cr'] += $result['total_cr'];
                    $data['current_assets_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('current_assets', $data, $value, $balance, $pre_balance, $result);

                $data['total_current_asset'] += $balance;
                $data['pre_total_current_asset'] += $pre_balance;

                foreach ($child_current_assets as $value2) {

                    $query2 = $this->account_latest_balance($value2, $request);
                    $previous_query2 = $this->account_previous_balance($value2, $request);
                    $data['current_assets'][$value2->id]['child'] = "yes";

                    $query2_balance = $query2 ? $query2->balance : 0;
                    $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                    $balance = $query2_balance;

                    $result = [];
                    if ($request->trail_balance) {
                        $result = $this->create_trail_balance($value2, $request->company_id);
                        $data['current_assets_total_cr'] += $result['total_cr'];
                        $data['current_assets_total_dr'] += $result['total_dr'];
                    }

                    $data = $this->generate_account_final_report('current_assets', $data, $value2, $balance, $pre_balance, $result);


                    $data['total_current_asset'] += $balance;
                    $data['pre_total_current_asset'] += $pre_balance;

                }

            } else {

                $query = $this->account_latest_balance($value, $request);
                $previous_query = $this->account_previous_balance($value, $request);


                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $result = [];
                if ($request->trail_balance) {
                    $result = $this->create_trail_balance($value, $request->company_id);
                    $data['current_assets_total_cr'] += $result['total_cr'];
                    $data['current_assets_total_dr'] += $result['total_dr'];
                }

                $data = $this->generate_account_final_report('current_assets', $data, $value, $balance, $pre_balance, $result);

                $data['total_current_asset'] += $balance;
                $data['pre_total_current_asset'] += $pre_balance;

            }

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Current Assets <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-current-assets', $data);

        }

    }

    public function advance_prepayment_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $advance_prepayment = AccountType::where('name', '=', "advance_deposits&_prepayments")->select('id')->first();
        $advance_prepayments = AccountType::where('id', $advance_prepayment->id)->orwhere('parent_id', $advance_prepayment->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        $data['total_advance_prepayment'] = 0;
        $data['pre_total_advance_prepayment'] = 0;


        if ($request->trail_balance) {
            $data['advance_prepayments_total_cr'] = 0;
            $data['advance_prepayments_total_dr'] = 0;
        }

        $data['advance_prepayments'] = [];

        foreach ($advance_prepayments as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);


            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['advance_prepayments_total_cr'] += $result['total_cr'];
                $data['advance_prepayments_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('advance_prepayments', $data, $value, $balance, $pre_balance, $result);


            $data['total_advance_prepayment'] += $balance;
            $data['pre_total_advance_prepayment'] += $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {
            $data['report_title'] = "Advance Deposit & Prepayment  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-advance-prepayment', $data);
        }

    }

    public function fixed_deposits_receipts_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $fixed_deposits_receipt = AccountType::where('name', '=', "fixed_deposits_receipts")->select('id')->first();
        $fixed_deposits_receipts = AccountType::where('id', $fixed_deposits_receipt->id)->orwhere('parent_id', $fixed_deposits_receipt->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_fixed_deposits_receipt'] = 0;
        $data['pre_total_fixed_deposits_receipt'] = 0;

        if ($request->trail_balance) {
            $data['fixed_deposits_receipts_total_cr'] = 0;
            $data['fixed_deposits_receipts_total_dr'] = 0;
        }

        $data['fixed_deposits_receipts'] = [];
        foreach ($fixed_deposits_receipts as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['fixed_deposits_receipts_total_cr'] += $result['total_cr'];
                $data['fixed_deposits_receipts_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('fixed_deposits_receipts', $data, $value, $balance, $pre_balance, $result);

            $data['total_fixed_deposits_receipt'] += $balance;
            $data['pre_total_fixed_deposits_receipt'] += $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Fixed Deposits Receipt  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-fixed_deposits_receipt', $data);

        }

    }

    public function due_companies_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $due_from_affiliated_company = AccountType::where('name', '=', "due_from_affiliated_company")->select('id')->first();
        $due_from_affiliated_companies = AccountType::where('id', $due_from_affiliated_company->id)->orwhere('parent_id', $due_from_affiliated_company->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_due_affiliated_company'] = 0;
        $data['pre_total_due_affiliated_company'] = 0;


        if ($request->trail_balance) {
            $data['due_companies_total_cr'] = 0;
            $data['due_companies_total_dr'] = 0;
        }

        $data['due_companies'] = [];
        foreach ($due_from_affiliated_companies as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['due_companies_total_cr'] += $result['total_cr'];
                $data['due_companies_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('due_companies', $data, $value, $balance, $pre_balance, $result);

            $data['total_due_affiliated_company'] += $balance;
            $data['pre_total_due_affiliated_company'] += $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Due Affiliated Company  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-due-affiliated', $data);


        }

    }

    public function cash_bank_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['total_cash_bank'] = 0;
        $data['pre_total_cash_bank'] = 0;
        $data['cash_banks'] = [];


        if ($request->trail_balance) {
            $data['cash_banks_total_cr'] = 0;
            $data['cash_banks_total_dr'] = 0;
        }

        $cash_bank_account = AccountType::authCompany()->whereIn('name', ['cash', 'bank'])->pluck('id')->toArray();
        $cash_banks = AccountType::whereIntegerInRaw('id', $cash_bank_account)->orwhereIn('parent_id', $cash_bank_account);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($cash_banks as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;



            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['cash_banks_total_cr'] += $result['total_cr'];
                $data['cash_banks_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('cash_banks', $data, $value, $balance, $pre_balance, $result);

            $data['total_cash_bank'] += $balance;
            $data['pre_total_cash_bank'] += $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Cash Bank  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-cash-bank', $data);


        }

    }

    public function bank_overdraft_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['total_over_bank'] = 0;
        $data['pre_total_over_bank'] = 0;
        $data['over_banks'] = [];


        if ($request->trail_balance) {
            $data['over_banks_total_cr'] = 0;
            $data['over_banks_total_dr'] = 0;
        }

        $over_bank_account = AccountType::authCompany()->where('name', 'current_liabilities')->pluck('id')->toArray();
        $over_bank = AccountType::whereIntegerInRaw('parent_id', $over_bank_account)->pluck('id')->toArray();
        $over_banks = CashOrBankAccount::whereIntegerInRaw('account_type_id', $over_bank)->pluck('account_type_id')->toArray();

        $over_banks = AccountType::whereIntegerInRaw('id', $over_banks);
        $over_banks = $this->authCompany($over_banks, $request);
        $over_banks = $over_banks->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($over_banks as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['over_banks_total_cr'] += $result['total_cr'];
                $data['over_banks_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('over_banks', $data, $value, $balance, $pre_balance, $result, '-');

            $data['total_over_bank'] += (-1) * $balance;
            $data['pre_total_over_bank'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Bank Overdraft  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-over-banks', $data);
        }

    }

    public function trade_debtor_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        // Only Customer are Trade Debtors
        // who have Debit balance must be bigger than 0;
        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $customers = $this->authCompany($supplier_or_customers, $request);
        $customerAccountTypes = $customers->where('customer_type', 'customer')->get()->pluck('account_type_id')->toArray();

        $customers = AccountType::whereIntegerInRaw('id', $customerAccountTypes);
        $customers = $customers->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        if ($request->trail_balance) {
            $data['trade_debtors_total_cr'] = 0;
            $data['trade_debtors_total_dr'] = 0;
        }

        $data['trade_debtors'] = [];
        $data['total_trade_debtor'] = 0;
        $data['pre_total_trade_debtor'] = 0;

        foreach ($customers as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            // Note: Change on Request Azim company
            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['trade_debtors_total_cr'] += $result['total_cr'];
                $data['trade_debtors_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('trade_debtors', $data, $value, $balance, $pre_balance, $result);


            $data['total_trade_debtor'] += $balance;
            $data['pre_total_trade_debtor'] += $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Trade Debtor  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-trade-debtor', $data);
        }

    }

    public function sundry_creditor_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['sundry_creditors'] = [];
        $data['total_sundry_creditors'] = 0;
        $data['pre_total_sundry_creditors'] = 0;

        if ($request->trail_balance) {
            $data['sundry_creditors_total_cr'] = 0;
            $data['sundry_creditors_total_dr'] = 0;
        }

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $suppliers = $this->authCompany($supplier_or_customers, $request);
        $sundrys = $suppliers->onlySuppliers()->get()->pluck('account_type_id')->toArray();

        $sundry_creditors = AccountType::whereIntegerInRaw('id', $sundrys);
        $sundry_creditors = $sundry_creditors->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($sundry_creditors as $key => $value) {


            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            // Note: Change on Request Azim company
            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['sundry_creditors_total_cr'] += $result['total_cr'];
                $data['sundry_creditors_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('sundry_creditors', $data, $value, $balance, $pre_balance, $result, '-');

            $data['total_sundry_creditors'] += (-1) * $balance;
            $data['pre_total_sundry_creditors'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Sundry Creditors  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-sundry-creditors', $data);
        }


    }

    public function account_payable_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['account_payables'] = [];
        $data['total_account_payables'] = 0;
        $data['pre_total_account_payables'] = 0;

        if ($request->trail_balance) {
            $data['account_payables_total_cr'] = 0;
            $data['account_payables_total_dr'] = 0;
        }

        $accounts_payable = AccountType::where('name', '=', "accounts_payable")->select('id')->first();
        $accounts_payables = AccountType::where('id', $accounts_payable->id)->orwhere('parent_id', $accounts_payable->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($accounts_payables as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['account_payables_total_cr'] += $result['total_cr'];
                $data['account_payables_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('account_payables', $data, $value, $balance, $pre_balance, $result, '-');

            $data['total_account_payables'] += (-1) * $balance;
            $data['pre_total_account_payables'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Account Payable  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-account-payables', $data);
        }


    }

    public function account_receivable_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['account_receivables'] = [];
        $data['total_account_receivables'] = 0;
        $data['pre_total_account_receivables'] = 0;

        if ($request->trail_balance) {
            $data['account_receivables_total_cr'] = 0;
            $data['account_receivables_total_dr'] = 0;
        }

        $accounts_receivable = AccountType::where('name', '=', "accounts_receivable")->select('id')->first();
        $accounts_receivables = AccountType::where('id', $accounts_receivable->id)->orwhere('parent_id', $accounts_receivable->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($accounts_receivables as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);


            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['account_receivables_total_cr'] += $result['total_cr'];
                $data['account_receivables_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('account_receivables', $data, $value, $balance, $pre_balance, $result);

            $data['total_account_receivables'] += $balance;
            $data['pre_total_account_receivables'] += $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Account Receivable  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-account-receivables', $data);
        }


    }

    public function equity_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $equity = AccountType::where('name', '=', "equity")->select('id')->first();
        $equities = AccountType::where('id', $equity->id)->orwhere('parent_id', $equity->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        $data['total_equity'] = 0;
        $data['pre_total_equity'] = 0;

        if ($request->trail_balance) {
            $data['equities_total_cr'] = 0;
            $data['equities_total_dr'] = 0;
        }

        $data['equities'] = [];
        foreach ($equities as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['equities_total_cr'] += $result['total_cr'];
                $data['equities_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('equities', $data, $value, $balance, $pre_balance, $result);

            $data['total_equity'] += $balance;
            $data['pre_total_equity'] += $pre_balance;

        }


        $retained_earning = AccountType::where('name', 'retained_earnings')->first();
        $profitCheck = ProfitBalanceTrack::where('end_date','<', $this->fromDate)->orderBy('end_date', 'desc')->first();
        $pre_profitCheck = ProfitBalanceTrack::where('end_date','<', $this->pre_fromDate)->orderBy('end_date', 'desc')->first();

        if($profitCheck && !$request->trail_balance)
        {

            $re_key = count($equities)+1;
            $data['equities'][$re_key]['account_type_id'] = $retained_earning->id;
            $data['equities'][$re_key]['account_type_name'] = $retained_earning->display_name;
            $data['equities'][$re_key]['balance'] = $balance = $profitCheck->balance;
            $data['equities'][$re_key]['pre_balance'] = $pre_balance = $pre_profitCheck ? $profitCheck->balance : 0;

            // $balance = $profitCheck->balance;
            // $pre_balance = $pre_profitCheck ? $profitCheck->balance : 0;

            // $data = $this->generate_account_final_report('equities', $data, $retained_earning->id, $balance, $pre_balance, $result);

            $data['total_equity'] += $balance;
            $data['pre_total_equity'] += $pre_balance;
        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Equity  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-equity', $data);
        }


    }

    public function long_term_liability_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $non_current_liability = AccountType::where('name', '=', "long-term_liabilities")->select('id')->first();
        $non_current_liabilities = AccountType::where('id', $non_current_liability->id)->orwhere('parent_id', $non_current_liability->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_non_current_liability'] = 0;
        $data['pre_total_non_current_liability'] = 0;

        if ($request->trail_balance) {
            $data['non_current_liabilities_total_cr'] = 0;
            $data['non_current_liabilities_total_dr'] = 0;
        }

        $data['non_current_liabilities'] = [];
        foreach ($non_current_liabilities as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['non_current_liabilities_total_cr'] += $result['total_cr'];
                $data['non_current_liabilities_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('non_current_liabilities', $data, $value, $balance, $pre_balance, $result, '-');

            $data['total_non_current_liability'] += (-1) * $balance;
            $data['pre_total_non_current_liability'] += (-1) * $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Non Current Liability  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-non-current-liability', $data);
        }

    }

    public function current_liability_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $over_banks = CashOrBankAccount::whereNotNull('account_type_id')->pluck('account_type_id')->toArray();
        $current_liability = AccountType::where('name', '=', "current_liabilities")->select('id')->first();

        $not_current_liabilities = AccountType::whereIn('name', ['due_to_affiliated_company', 'liabilities_for_expenses', 'income_tax_payble', 'accounts_payable'])->pluck('id')->toArray();

        $not_current_liabilities = array_merge($not_current_liabilities, $over_banks);

        $current_liabilities = AccountType::whereIntegerNotInRaw('id', $not_current_liabilities)
            ->where(function ($query) use ($current_liability) {
                $query->where('id', $current_liability->id)
                    ->orwhere('parent_id', $current_liability->id);
            })->authCompany()
            ->select('id', 'display_name')
            ->orderBy('display_name', 'asc')->get();


        $data['total_current_liability'] = 0;
        $data['pre_total_current_liability'] = 0;


        if ($request->trail_balance) {
            $data['current_liabilities_total_cr'] = 0;
            $data['current_liabilities_total_dr'] = 0;
        }

        $data['current_liabilities'] = [];
        foreach ($current_liabilities as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['current_liabilities_total_cr'] += $result['total_cr'];
                $data['current_liabilities_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('current_liabilities', $data, $value, $balance, $pre_balance, $result, '-');


            $data['total_current_liability'] += (-1) * $balance;
            $data['pre_total_current_liability'] += (-1) * $pre_balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Current Liability  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-current-liability', $data);
        }

    }

    public function due_to_affiliated_company_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['total_due_affiliated'] = 0;
        $data['due_to_affiliated_companies'] = [];

        if ($request->trail_balance) {
            $data['due_to_affiliated_companies_total_cr'] = 0;
            $data['due_to_affiliated_companies_total_dr'] = 0;
        }

        $due_to_affiliated_company = AccountType::where('name', '=', "due_to_affiliated_company")->select('id')->first();
        $due_to_affiliated_companies = AccountType::where('id', $due_to_affiliated_company->id)->orwhere('parent_id', $due_to_affiliated_company->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_due_affiliated'] = 0;
        $data['pre_total_due_affiliated'] = 0;

        foreach ($due_to_affiliated_companies as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['due_to_affiliated_companies_total_cr'] += $result['total_cr'];
                $data['due_to_affiliated_companies_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('due_to_affiliated_companies', $data, $value, $balance, $pre_balance, $result, '-');


            $data['total_due_affiliated'] += (-1) * $balance;
            $data['pre_total_due_affiliated'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Due to Affiliated Companies  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-due_affiliated_company', $data);
        }

    }

    public function liability_for_expense_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);
        $data['total_liabilities_expenses'] = 0;

        if ($request->trail_balance) {
            $data['liabilities_expenses_total_cr'] = 0;
            $data['liabilities_expenses_total_dr'] = 0;
        }

        $liabilities_expense = AccountType::where('name', '=', "liabilities_for_expenses")->select('id')->first();
        $liabilities_expenses = AccountType::where('id', $liabilities_expense->id)->orwhere('parent_id', $liabilities_expense->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_liabilities_expenses'] = 0;
        $data['pre_total_liabilities_expenses'] = 0;


        $data['liabilities_expenses'] = [];
        foreach ($liabilities_expenses as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['liabilities_expenses_total_cr'] += $result['total_cr'];
                $data['liabilities_expenses_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('liabilities_expenses', $data, $value, $balance, $pre_balance, $result, '-');


            $data['total_liabilities_expenses'] += (-1) * $balance;
            $data['pre_total_liabilities_expenses'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Liability Expenses  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-liabilities-expenses', $data);

        }

    }

    public function income_tax_payable_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $data['total_income_tax_payable'] = 0;


        if ($request->trail_balance) {
            $data['income_tax_payables_total_cr'] = 0;
            $data['income_tax_payables_total_dr'] = 0;
        }

        $income_tax_payable = AccountType::where('name', '=', "income_tax_payble")->select('id')->first();
        $income_tax_payables = AccountType::where('id', $income_tax_payable->id)->orwhere('parent_id', $income_tax_payable->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['total_income_tax_payable'] = 0;
        $data['pre_total_income_tax_payable'] = 0;

        $data['income_tax_payables'] = [];
        foreach ($income_tax_payables as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['income_tax_payables_total_cr'] += $result['total_cr'];
                $data['income_tax_payables_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('income_tax_payables', $data, $value, $balance, $pre_balance, $result, '-');


            $data['total_income_tax_payable'] += (-1) * $balance;
            $data['pre_total_income_tax_payable'] += (-1) * $pre_balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Income Tax Payable  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-income-tax-payable', $data);
        }


    }

    public function cost_of_sold_report(Request $request, $data = [])
    {
        $this->set_from_to_date($request);

        $cost_of_sold = AccountType::where('name', '=', "cost_of_goods_sold")->select('id')->first();
        $cost_of_sold_items = AccountType::where('id', $cost_of_sold->id)->orwhere('parent_id', $cost_of_sold->id)->authCompany()->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        if ($request->trail_balance) {
            $data['cost_of_sold_items_total_cr'] = 0;
            $data['cost_of_sold_items_total_dr'] = 0;
            $total_cost_of_sold = 0;
            $pre_total_cost_of_sold = 0;
        }else{

            if($data) {
                $total_cost_of_sold = $data['total_ABC'];
                $pre_total_cost_of_sold = $data['pre_total_ABC'];

            }else{

                $total_cost_of_sold = 0;
                $pre_total_cost_of_sold = 0;
            }
        }

        $data['cost_of_sold_items'] = [];

        foreach ($cost_of_sold_items as $key => $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['cost_of_sold_items_total_cr'] += $result['total_cr'];
                $data['cost_of_sold_items_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('cost_of_sold_items', $data, $value, $balance, $pre_balance, $result);


            $total_cost_of_sold += $balance;
            $pre_total_cost_of_sold += $pre_balance;
        }

        $data['total_cost_of_sold'] = $total_cost_of_sold;
        $data['pre_total_cost_of_sold'] = $pre_total_cost_of_sold;


        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Cost of Good Sold  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-cost-of-sold', $data);
        }

    }

    public function sales_report(Request $request, $data = [])
    {
        $sales = AccountType::where('name', '=', "sales")->select('id', 'display_name')->get();

        $data['total_sales'] = 0;
        $data['pre_total_sales'] = 0;


        if ($request->trail_balance) {
            $data['sales_total_cr'] = 0;
            $data['sales_total_dr'] = 0;
        }

        $data['sales'] = [];

        foreach ($sales as $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);


            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['sales_total_cr'] += $result['total_cr'];
                $data['sales_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('sales', $data, $value, $balance, $pre_balance, $result, '-');

            $data['pre_total_sales'] += $pre_balance;
            $data['total_sales'] += $balance;


        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Sales  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-sales', $data);
        }
    }

    public function purchases_report(Request $request, $data = [])
    {
        $purchases = AccountType::where('name', '=', "purchase")->select('id', 'display_name')->get();

        $data['total_purchases'] = 0;
        $data['pre_total_purchases'] = 0;


        if ($request->trail_balance) {
            $data['purchases_total_cr'] = 0;
            $data['purchases_total_dr'] = 0;
        }

        $data['purchases'] = [];

        foreach ($purchases as $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);


            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['purchases_total_cr'] += $result['total_cr'];
                $data['purchases_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('purchases', $data, $value, $balance, $pre_balance, $result);

            $data['pre_total_purchases'] += $pre_balance;
            $data['total_purchases'] += $balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

            $data['report_title'] = "Purchase  <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
            $data = $this->company($data);
            return view('member.reports.balance_sheet.head_accounts.print-purchases', $data);
        }
    }

    public function undefined_parent_report(Request $request, $data = [])
    {
        $array = Session::get('account_id');
        $arr = array_map('intval', array_map('trim', explode(',', $array)));

        $accounts = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('transaction_method', '!=', 'Initial');
        })
            ->where('date', '<=', $this->toDate)
            ->where('company_id', $request->company_id)
            ->whereIntegerNotInRaw('account_type_id', $arr)
            ->distinct('account_type_id')
            ->pluck('account_type_id')
            ->toArray();

        $accountTypes = AccountType::whereIntegerInRaw('id', $accounts)->withTrashed()->select('id', 'display_name')->orderBy('display_name')->get();

        $data['total_no_parent'] = 0;
        $data['pre_total_no_parent'] = 0;


        if ($request->trail_balance) {
            $data['no_parent_total_cr'] = 0;
            $data['no_parent_total_dr'] = 0;
        }

        $data['no_parents'] = [];

        foreach ($accountTypes as $value) {

            $query = $this->account_latest_balance($value, $request);
            $previous_query = $this->account_previous_balance($value, $request);

            $query_balance = $query ? $query->balance : 0;
            $pre_balance = $previous_query ? $previous_query->balance : 0;
            $balance = $query_balance;

            $result = [];
            if ($request->trail_balance) {
                $result = $this->create_trail_balance($value, $request->company_id);
                $data['no_parent_total_cr'] += $result['total_cr'];
                $data['no_parent_total_dr'] += $result['total_dr'];
            }

            $data = $this->generate_account_final_report('no_parents', $data, $value, $balance, $pre_balance, $result);

            $data['pre_total_no_parent'] += $pre_balance;
            $data['total_no_parent'] += $balance;

        }

        if ($request->returnData) {
            return $data;
        } else {

        }
    }

    protected function total_dr($value, $company_id)
    {
        $total_dr = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('transaction_method', '!=', 'Initial');
        })
            ->where('date', '>=', $this->fromDate)
            ->where('date', '<=', $this->toDate)
            ->where('transaction_type', 'dr')
            ->where('account_type_id', $value->id)
            ->where('company_id', $company_id)
            ->sum('amount');

        return $total_dr;

    }


    protected function total_dr_cr($value, $company_id)
    {
        $total = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('transaction_method', '!=', 'Initial');
        })
            ->where('date', '>=', $this->fromDate)
            ->where('date', '<=', $this->toDate)
            ->where('account_type_id', $value->id)
            ->where('company_id', $company_id)
            ->groupBy('transaction_type')
            ->select('transaction_type',DB::raw("SUM(transaction_details.amount) as tr_amount"))
            ->get();

        return $total;

    }

    protected function total_cr($value, $company_id)
    {
        $total_cr = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('transaction_method', '!=', 'Initial');
        })
            ->where('date', '>=', $this->fromDate)
            ->where('date', '<=', $this->toDate)
            ->where('transaction_type', 'cr')
            ->where('account_type_id', $value->id)
            ->where('company_id', $company_id)
            ->sum('amount');

        return $total_cr;

    }

    public function generate_account_final_report($key_name, $data, $value, $balance, $pre_balance, $result, $balanceOperator = "+")
    {

        if( (isset($data['trail_balance']) && $data['trail_balance']) || $balance != 0 || $pre_balance != 0) {

            $balanceOperator = isset($data['trail_balance']) && $data['trail_balance'] ? '+' : $balanceOperator;

            $this->array_value($value);
            $this->accountFinalReport->setValue($key_name, $value, $balanceOperator);
            $data = $this->accountFinalReport->getAccountBalance($data, $balance);
            $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

            if (isset($data['trail_balance']) && $data['trail_balance'])
                $data = $this->accountFinalReport->getAccountDrCrBalance($data, $result);

            return $data;
        }else{

            unset($data[$key_name][$value->id]);

            return $data;

        }

//        return $data;
    }

    public function account_latest_balance($value, $request)
    {
        $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
        $query = $this->authCompany($query, $request);
        $query = $query->first();

        return $query;
    }

    public function account_previous_balance($value, $request)
    {
        $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
        $previous_query = $this->authCompany($previous_query, $request);
        $previous_query = $previous_query->first();

        return $previous_query;
    }

    public function account_pre_latest_balance($value, $request)
    {
        $previous_query = $value->account_head_balance()->previousAccountBalance($this->pre_fromDate);
        $previous_query = $this->authCompany($previous_query, $request);
        $previous_query = $previous_query->first();

        return $previous_query;
    }

    public function array_value($arr)
    {
        $array = Session::get('account_id');
        $array = $array != "" ? $array . ", " . $arr->id : $arr->id;
        Session::put('account_id', $array);
    }

    public function arraylist_value($arr)
    {
        $array = '';
        foreach ($arr as $value)
        {
            $array = $array != "" ? $array . ", " . $value->id : $value->id;
        }
        print_r($array);exit;
    }

}
