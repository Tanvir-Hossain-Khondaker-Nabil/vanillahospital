<?php


namespace App\Http\Controllers\Member\Reports;


use App\Http\ECH\AccountFinalReport;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\SupplierOrCustomer;
use Illuminate\Http\Request;

class AccountBalanceReportController extends BaseReportController
{
    protected $accountFinalReport;

    public function __construct()
    {
        $this->accountFinalReport = new AccountFinalReport();
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

    public function fixed_asset_report(Request $request, $data = '')
    {

        $this->set_from_to_date($request);

        $fixed_asset = AccountType::where('name', '=', "fixed_assets")->select('id')->first();
        $fixed_assets = AccountType::where('parent_id', $fixed_asset->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $fixed_asset_balance = $fixed_asset->account_head_balance();
        $fixed_asset_balance = $fixed_asset_pre_balance = $this->authCompany($fixed_asset_balance, $request);
        $data['fixed_asset'] = $fixed_asset_balance = $fixed_asset_balance->latestAccountBalance($this->toDate)->first();
        $data['pre_fixed_asset'] = $fixed_asset_pre_balance = $fixed_asset_pre_balance->previousAccountBalance($this->fromDate)->first();

        $data['total_fixed'] = $fixed_asset_balance ? $fixed_asset_balance->balance : 0;
        $data['pre_total_fixed'] = $fixed_asset_pre_balance ? $fixed_asset_pre_balance->balance : 0;

        if($request->trail_balannce)
        {
            $data['fixed_total_cr'] = 0;
            $data['fixed_total_dr'] = 0;
        }

        $data['fixed_assets'] = [];
        foreach ($fixed_assets as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $data = $this->generate_account_final_report('fixed_assets', $data, $value, $balance, $pre_balance);

                if($request->trail_balannce)
                {
                    $total_cr = $this->total_cr($value);
                    $total_dr = $this->total_dr($value);

                    $data['total_cr'] = $total_cr;
                    $data['total_dr'] = $total_dr;
                    $data['fixed_total_cr'] += $total_cr;
                    $data['fixed_total_dr'] += $total_dr;
                }


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
        $expenses = AccountType::where('parent_id', $expense->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $ex_balance = $expense->account_head_balance();
        $ex_balance = $pre_ex_balance = $ex_balance->authCompany($ex_balance, $request);
        $ex_balance = $ex_balance->latestAccountBalance($this->toDate)->first();
        $pre_ex_balance = $pre_ex_balance->previousAccountBalance($this->fromDate)->first();
        $data['ex_balance'] = $data['total_expenses'] = $ex_balance ? $ex_balance->balance : 0;
        $data['pre_ex_balance'] = $data['pre_total_expenses'] = $pre_ex_balance ? $pre_ex_balance->balance : 0;

        $data['expenses'] = [];

        foreach ($expenses as $key => $value) {
            $child_expenses = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

            if (count($child_expenses) > 0) {
                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                $data['expenses'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance-$pre_balance;

                $this->accountFinalReport->setValue('expenses', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                $data['total_expenses'] += $balance;
                $data['pre_total_expenses'] += $pre_balance;

                foreach ($child_expenses as $value2) {
                    $query2 = $value2->account_head_balance()->latestAccountBalance($this->toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value2->account_head_balance()->previousAccountBalance($this->fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();


                    if ($query2) {
                        $data['expenses'][$value2->id]['child'] = "yes";

                        $query2_balance = $query2 ? $query2->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                        $balance = $query2_balance-$pre_balance;

                        $this->accountFinalReport->setValue('expenses', $value2);
                        $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                        $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                        $data['total_expenses'] += $balance;
                        $data['pre_total_expenses'] += $pre_balance;

                    }
                }


            } else {

                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                if ($query) {

                    $query_balance = $query ? $query->balance : 0;
                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance-$pre_balance;

                    $this->accountFinalReport->setValue('expenses', $value);
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $incomes = $incomes->authCompany($incomes, $request)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $in_balance = $income->account_head_balance();
        $in_balance = $pre_in_balance = $in_balance->authCompany($in_balance, $request);
        $in_balance = $in_balance->latestAccountBalance($this->toDate)->first();
        $pre_in_balance = $pre_in_balance->previousAccountBalance($this->fromDate)->first();


        $data['in_balance'] = $data['total_incomes'] = $in_balance ? (-1) * $in_balance->balance : 0;
        $data['pre_total_incomes'] = $pre_in_balance ? (-1) * $pre_in_balance->balance : 0;
        $data['incomes'] = [];

        foreach ($incomes as $key => $value) {
            $child_incomes = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();
            if (count($child_incomes) > 0) {

                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();


                $data['incomes'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance - $pre_balance;

                $this->accountFinalReport->setValue('incomes', $value, '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                $data['total_incomes'] += (-1)*$balance;
                $data['pre_total_incomes'] += (-1)*$pre_balance;

                foreach ($child_incomes as $value2) {

                    $query2 = $value2->account_head_balance()->latestAccountBalance($this->toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value2->account_head_balance()->previousAccountBalance($this->fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();


                    if ($query2) {
                        $data['incomes'][$value2->id]['child'] = "yes";

                        $query2_balance = $query2 ? $query2->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                        $balance = $query2_balance - $pre_balance;

                        $this->accountFinalReport->setValue('incomes', $value2, '-');
                        $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                        $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                        $data['total_incomes'] += (-1)*$balance;
                        $data['pre_total_incomes'] += (-1)*$pre_balance;
                    }
                }
            } else {

                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();


                if ($query) {
                    $query_balance = $query ? $query->balance : 0;
                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance - $pre_balance;

                    $this->accountFinalReport->setValue('incomes', $value, '-');
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $current_assets = AccountType::whereNotIn('name', ['cash', 'bank', 'advance_deposits&_prepayments', 'due_from_affiliated_company', 'fixed_deposits_receipts'])->where('parent_id', $current_asset->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $current_asset_balance = $current_asset->account_head_balance();
        $current_asset_balance = $this->authCompany($current_asset_balance, $request);
        $data_current_asset = $current_asset_balance->latestAccountBalance($this->toDate)->first();
        $pre_data_current_asset = $current_asset_balance->previousAccountBalance($this->fromDate)->first();

        $data['current_asset_balance'] = $data['total_current_asset'] = $data_current_asset ? $data_current_asset->balance : 0;
        $data['pre_current_asset_balance'] = $data['pre_total_current_asset'] = $pre_data_current_asset ? $pre_data_current_asset->balance : 0;

        $data['current_assets'] = [];

        foreach ($current_assets as $key => $value) {

            $child_current_assets = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

            if (count($child_current_assets) > 0) {

                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                $data['current_assets'][$value->id]['parent'] = "yes";

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('current_assets', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                $data['total_current_asset'] += $balance;
                $data['pre_total_current_asset'] += $pre_balance;

                foreach ($child_current_assets as $value2) {
                    $query2 = $value2->account_head_balance()->latestAccountBalance($this->toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value2->account_head_balance()->previousAccountBalance($this->fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();

                    if ($query2) {
                        $data['current_assets'][$value2->id]['child'] = "yes";
                        $query2_balance = $query2 ? $query2->balance : 0;
                        $pre_balance = $previous_query2 ? $previous_query2->balance : 0;
                        $balance = $query2_balance;

                        $this->accountFinalReport->setValue('current_assets', $value2);
                        $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                        $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

                        $data['total_current_asset'] += $balance;
                        $data['pre_total_current_asset'] += $pre_balance;
                    }
                }

            } else {

                $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                if ($query) {
                    $query_balance = $query ? $query->balance : 0;
                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance;

                    $this->accountFinalReport->setValue('current_assets', $value);
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $advance_prepayments = AccountType::where('parent_id', $advance_prepayment->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $advance_prepayment_balance = $advance_prepayment->account_head_balance();
        $advance_prepayment_balance = $this->authCompany($advance_prepayment_balance, $request);
        $data_advance_prepayment_balance = $advance_prepayment_balance->latestAccountBalance($this->toDate)->first();
        $pre_data_advance_prepayment_balance = $advance_prepayment_balance->previousAccountBalance($this->fromDate)->first();

        $data['total_advance_prepayment'] = $data_advance_prepayment_balance ? $data_advance_prepayment_balance->balance : 0;
        $data['pre_total_advance_prepayment'] = $pre_data_advance_prepayment_balance ? $pre_data_advance_prepayment_balance->balance : 0;

        $data['advance_prepayments'] = [];
        foreach ($advance_prepayments as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('advance_prepayments', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $fixed_deposits_receipts = AccountType::where('parent_id', $fixed_deposits_receipt->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $fixed_deposits_receipt_balance = $fixed_deposits_receipt->account_head_balance();
        $fixed_deposits_receipt_balance = $this->authCompany($fixed_deposits_receipt_balance, $request);
        $data_fixed_deposits_receipt_balance = $fixed_deposits_receipt_balance->latestAccountBalance($this->toDate)->first();
        $pre_fixed_deposits_receipt_balance = $fixed_deposits_receipt_balance->previousAccountBalance($this->fromDate)->first();

        $data['total_fixed_deposits_receipt'] = $data_fixed_deposits_receipt_balance ? $data_fixed_deposits_receipt_balance->balance : 0;
        $data['pre_total_fixed_deposits_receipt'] = $pre_fixed_deposits_receipt_balance ? $pre_fixed_deposits_receipt_balance->balance : 0;

        $data['fixed_deposits_receipts'] = [];
        foreach ($fixed_deposits_receipts as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('fixed_deposits_receipts', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $due_from_affiliated_companies = AccountType::where('parent_id', $due_from_affiliated_company->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $due_from_affiliated_company_balance = $due_from_affiliated_company->account_head_balance();
        $due_from_affiliated_company_balance = $this->authCompany($due_from_affiliated_company_balance, $request);
        $data_due_from_affiliated_company_balance = $due_from_affiliated_company_balance->latestAccountBalance($this->toDate)->first();
        $pre_due_from_affiliated_company_balance = $due_from_affiliated_company_balance->previousAccountBalance($this->fromDate)->first();

        $data['total_due_affiliated_company'] = $data_due_from_affiliated_company_balance ? $data_due_from_affiliated_company_balance->balance : 0;
        $data['pre_total_due_affiliated_company'] = $pre_due_from_affiliated_company_balance ? $pre_due_from_affiliated_company_balance->balance : 0;

        $data['due_companies'] = [];
        foreach ($due_from_affiliated_companies as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('due_companies', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $cash_bank_account = AccountType::authCompany()->whereIn('name', ['cash', 'bank'])->pluck('id')->toArray();
        $cash_banks = AccountType::whereIn('id', [3, 4])->orwhereIn('parent_id', $cash_bank_account);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        foreach ($cash_banks as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;

                if ($query_balance != 0) {

                    $query_balance = $query ? $query->balance : 0;
                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance;

                    $this->accountFinalReport->setValue('cash_banks', $value);
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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


        $over_bank_account = AccountType::authCompany()->where('name', 'current_liabilities')->pluck('id')->toArray();
        $over_bank = AccountType::whereIn('parent_id', $over_bank_account)->pluck('id')->toArray();
        $over_banks = CashOrBankAccount::whereIn('account_type_id', $over_bank)->pluck('account_type_id')->toArray();

        $over_banks = AccountType::whereIn('id', $over_banks);
        $over_banks = $this->authCompany($over_banks, $request);
        $over_banks = $over_banks->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        foreach ($over_banks as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $query_balance = $query ? $query->balance : 0;

                if ($query_balance != 0) {

                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance;

                    $this->accountFinalReport->setValue('over_banks', $value, '-');
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $customers = AccountType::whereIn('id', $customerAccountTypes);
        $customers = $customers->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data['trade_debtors'] = [];
        $data['total_trade_debtor'] = 0;
        $data['pre_total_trade_debtor'] = 0;

        foreach ($customers as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                // Note: Change on Request Azim company
                $query_balance = $query ? $query->balance : 0;

                if ($query_balance != 0) {

                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance;

                    $this->accountFinalReport->setValue('trade_debtors', $value);
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $suppliers = $this->authCompany($supplier_or_customers, $request);
        $sundrys = $suppliers->onlySuppliers()->get()->pluck('account_type_id')->toArray();

        $sundry_creditors = AccountType::whereIn('id', $sundrys);
        $sundry_creditors = $sundry_creditors->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($sundry_creditors as $key => $value) {


            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                // Note: Change on Request Azim company
                $query_balance = $query ? $query->balance : 0;

                if ($query_balance != 0) {
                    $pre_balance = $previous_query ? $previous_query->balance : 0;
                    $balance = $query_balance;

                    $this->accountFinalReport->setValue('sundry_creditors', $value, '-');
                    $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                    $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $accounts_payable = AccountType::where('name', '=', "accounts_payable")->select('id')->first();
        $accounts_payables = AccountType::where('parent_id', $accounts_payable->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($accounts_payables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('account_payables', $value,
                    '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $accounts_receivable = AccountType::where('name', '=', "accounts_receivable")->select('id')->first();
        $accounts_receivables = AccountType::where('parent_id', $accounts_receivable->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        foreach ($accounts_receivables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('account_receivables', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $equities = AccountType::where('parent_id', $equity->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $equity_balance = $equity->account_head_balance();
        $equity_balance = $this->authCompany($equity_balance, $request);
        $data_equity = $equity_balance->latestAccountBalance($this->toDate)->first();
        $pre_equity = $equity_balance->previousAccountBalance($this->toDate)->first();

        $data['equity_balance'] = $data_equity ? $data_equity->balance : 0;
        $data['pre_equity_balance'] = $pre_equity ? $pre_equity->balance : 0;

        $data['equities'] = [];
        foreach ($equities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('equities', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $non_current_liabilities = AccountType::where('parent_id', $non_current_liability->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();


        $data_non_current_liability = $non_current_liability->account_head_balance();
        $data_non_current_liability = $pre_non_current_liability = $this->authCompany($data_non_current_liability, $request);
        $data_non_current_liability = $data_non_current_liability->latestAccountBalance($this->toDate)->first();
        $pre_non_current_liability = $pre_non_current_liability->previousAccountBalance($this->fromDate)->first();

        $data['non_current_liability_balance'] = $data['total_non_current_liability'] = $data_non_current_liability ? (-1) * $data_non_current_liability->balance : 0;
        $data['pre_non_current_liability_balance'] = $data['pre_total_non_current_liability'] = $pre_non_current_liability ? (-1) * $pre_non_current_liability->balance : 0;

        $data['non_current_liabilities'] = [];
        foreach ($non_current_liabilities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('non_current_liabilities', $value,
                    '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $over_banks = CashOrBankAccount::whereNotNull('account_type_id')->pluck('account_type_id')->toArray();
        $current_liability = AccountType::where('name', '=', "current_liabilities")->select('id')->first();

        $not_current_liabilities = AccountType::whereIn('name', ['due_to_affiliated_company', 'liabilities_for_expenses', 'income_tax_payble', 'accounts_payable'])->pluck('id')->toArray();

        $not_current_liabilities = array_merge($not_current_liabilities, $over_banks);

        $current_liabilities = AccountType::whereNotIn('id', $not_current_liabilities)
            ->where(function ($query) use ($current_liability) {
                $query->where('id', $current_liability->id)
                    ->orwhere('parent_id', $current_liability->id);
            })->authCompany()
            ->select('id', 'display_name')
            ->orderBy('display_name', 'asc')->get();

        $data_current_liability = $current_liability->account_head_balance();
        $data_current_liability = $pre_current_liability = $this->authCompany($data_current_liability, $request);
        $data_current_liability = $data_current_liability->latestAccountBalance($this->toDate)->first();
        $pre_current_liability = $pre_current_liability->previousAccountBalance($this->fromDate)->first();

        $data['current_liability_balance'] = $data['total_current_liability'] = $data_current_liability ? (-1) * $data_current_liability->balance : 0;
        $data['pre_current_liability_balance'] = $data['pre_total_current_liability'] = $pre_current_liability ? (-1) * $pre_current_liability->balance : 0;

        $data['current_liabilities'] = [];
        foreach ($current_liabilities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('current_liabilities', $value,
                    '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $due_to_affiliated_company = AccountType::where('name', '=', "due_to_affiliated_company")->select('id')->first();
        $due_to_affiliated_companies = AccountType::where('parent_id', $due_to_affiliated_company->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data_due_to_affiliated_company = $due_to_affiliated_company->account_head_balance();
        $data_due_to_affiliated_company = $pre_due_to_affiliated_company = $this->authCompany($data_due_to_affiliated_company, $request);
        $data_due_to_affiliated_company = $data_due_to_affiliated_company->latestAccountBalance($this->toDate)->first();
        $pre_due_to_affiliated_company = $pre_due_to_affiliated_company->previousAccountBalance($this->fromDate)->first();

        $data['due_to_affiliated_company_balance'] = $data['total_due_affiliated'] = $data_due_to_affiliated_company ? (-1) * $data_due_to_affiliated_company->balance : 0;
        $data['pre_due_to_affiliated_company_balance'] = $data['pre_total_due_affiliated'] = $pre_due_to_affiliated_company ? (-1) * $pre_due_to_affiliated_company->balance : 0;

        foreach ($due_to_affiliated_companies as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('due_to_affiliated_companies', $value, '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $liabilities_expense = AccountType::where('name', '=', "liabilities_for_expenses")->select('id')->first();

        $data_liabilities_expense = $liabilities_expense->account_head_balance();
        $data_liabilities_expense = $pre_liabilities_expense = $this->authCompany($data_liabilities_expense, $request);
        $data_liabilities_expense = $data_liabilities_expense->latestAccountBalance($this->toDate)->first();
        $pre_liabilities_expense = $pre_liabilities_expense->previousAccountBalance($this->toDate)->first();

        $data['liabilities_expenses_balance'] = $data['total_liabilities_expenses'] = $data_liabilities_expense ? (-1) * $data_liabilities_expense->balance : 0;
        $data['pre_liabilities_expenses_balance'] = $data['pre_total_liabilities_expenses'] = $pre_liabilities_expense ? (-1) * $pre_liabilities_expense->balance : 0;

        $liabilities_expenses = AccountType::where('parent_id', $liabilities_expense->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();
        $data['liabilities_expenses'] = [];
        foreach ($liabilities_expenses as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('liabilities_expenses', $value, '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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

        $income_tax_payable = AccountType::where('name', '=', "income_tax_payble")->select('id')->first();
        $income_tax_payables = AccountType::where('parent_id', $income_tax_payable->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $data_income_tax_payable = $income_tax_payable->account_head_balance();
        $data_income_tax_payable = $pre_income_tax_payable = $this->authCompany($data_income_tax_payable, $request);
        $data_income_tax_payable = $data_income_tax_payable->latestAccountBalance($this->toDate)->first();
        $pre_income_tax_payable = $pre_income_tax_payable->previousAccountBalance($this->fromDate)->first();

        $data['income_tax_payable_balance'] = $data['total_income_tax_payable'] = $data_income_tax_payable ? (-1) * $data_income_tax_payable->balance : 0;
        $data['pre_income_tax_payable_balance'] = $data['pre_total_income_tax_payable'] = $pre_income_tax_payable ? (-1) * $pre_income_tax_payable->balance : 0;

        $data['income_tax_payables'] = [];
        foreach ($income_tax_payables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('income_tax_payables', $value, '-');
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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
        $cost_of_sold_items = AccountType::where('parent_id', $cost_of_sold->id)->select('id', 'display_name')->orderBy('display_name', 'asc')->get();

        $cost_of_sold_balance = $cost_of_sold->account_head_balance();
        $cost_of_sold_balance = $pre_cost_of_sold_balance = $this->authCompany($cost_of_sold_balance, $request);
        $cost_of_sold_balance = $cost_of_sold_balance->latestAccountBalance($this->toDate)->first();
        $pre_cost_of_sold_balance = $pre_cost_of_sold_balance->previousAccountBalance($this->fromDate)->first();

        $data['cost_of_sold_balance'] = $cost_of_sold_balance ? $cost_of_sold_balance->balance : 0;
        $data['pre_cost_of_sold_balance'] = $pre_cost_of_sold_balance ? $pre_cost_of_sold_balance->balance : 0;

        $total_cost_of_sold = $data['openingStock'] + $data['total_purchases'] - $data['total_inventory'] + $data['cost_of_sold_balance'];
        $pre_total_cost_of_sold = $data['pre_openingStock'] + $data['pre_total_purchases'] - $data['pre_total_inventory'] + $data['pre_cost_of_sold_balance'];
        $data['cost_of_sold_items'] = [];

        foreach ($cost_of_sold_items as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($this->toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($this->fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                $query_balance = $query ? $query->balance : 0;
                $pre_balance = $previous_query ? $previous_query->balance : 0;
                $balance = $query_balance;

                $this->accountFinalReport->setValue('cost_of_sold_items', $value);
                $data = $this->accountFinalReport->getAccountBalance($data, $balance);
                $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

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


    public function create_trail_balance($value)
    {
        $result = [];
        $result['total_cr'] = $this->total_cr($value);
        $result['total_dr'] = $this->total_dr($value);

        return $result;
    }


    public function generate_account_final_report($key_name, $data, $value, $balance, $pre_balance){

        $this->accountFinalReport->setValue($key_name, $value);
        $data = $this->accountFinalReport->getAccountBalance($data, $balance);
        $data = $this->accountFinalReport->getAccountPreviousBalance($data, $pre_balance);

        return $data;

    }


}
