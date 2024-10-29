<?php

namespace App\Http\Controllers\Member\Reports;


use App\Models\Company;
use App\Models\FiscalYear;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceSheetControllerV3 extends BaseReportController
{

    public function index(Request $request)
    {

        if (isset(Auth::user()->company_id) && Auth::user()->company_id != null) {
            $request['company_id'] = Auth::user()->company_id;
        }

        $this->searchDate($request);
        $request['fromDate'] = $this->fromDate;
        $request['toDate'] = $this->toDate;
        $request['pre_toDate'] = $this->pre_toDate;
        $request['pre_fromDate'] =  $this->pre_fromDate;

        if (!$this->fromDate || !$this->toDate) {
            $status = ['type' => 'danger', 'message' => 'There is no data in that date/year for Balance Sheet'];
            return redirect()->back()->with('status', $status);
        }

        $data['fromDate'] = $this->fromDate;
        $data['toDate'] = $this->toDate;
        $data['pre_toDate'] = $this->pre_toDate;
        $data['pre_fromDate'] =  $this->pre_fromDate;
        $request['returnData'] = true;

        $inventoryReport = new InventoryReportController();
        $data = $inventoryReport->index($request, $data);
        $data = $inventoryReport->sale_details($request, $data);
        $data = $inventoryReport->purchase_details($request, $data);

        $accountBalanceReport = new AccountBalanceReportControllerV3();
        $data = $accountBalanceReport->fixed_asset_report($request, $data);
        $data = $accountBalanceReport->current_asset_report($request, $data);
        $data = $accountBalanceReport->advance_prepayment_report($request, $data);
        $data = $accountBalanceReport->fixed_deposits_receipts_report($request, $data);
        $data = $accountBalanceReport->due_companies_report($request, $data);



        $data = $accountBalanceReport->cash_bank_report($request, $data);
        $data = $accountBalanceReport->bank_overdraft_report($request, $data);


        $data = $accountBalanceReport->trade_debtor_report($request, $data);
        $data = $accountBalanceReport->sundry_creditor_report($request, $data);


        $data = $accountBalanceReport->account_payable_report($request, $data);
        $data = $accountBalanceReport->account_receivable_report($request, $data);


        $data = $accountBalanceReport->equity_report($request, $data);


        $data = $accountBalanceReport->long_term_liability_report($request, $data);
        $data = $accountBalanceReport->current_liability_report($request, $data);
        $data = $accountBalanceReport->due_to_affiliated_company_report($request, $data);
        $data = $accountBalanceReport->liability_for_expense_report($request, $data);
        $data = $accountBalanceReport->income_tax_payable_report($request, $data);


        $data = $accountBalanceReport->cost_of_sold_report($request, $data);
        $data = $accountBalanceReport->expense_report($request, $data);
        $data = $accountBalanceReport->income_report($request, $data);

        $data['net_profit'] = ($data['total_sales'] + $data['total_incomes']) - $data['total_expenses'] - $data['total_cost_of_sold'];

        $data['pre_net_profit'] = ($data['pre_total_sales'] + $data['pre_total_incomes']) - $data['pre_total_expenses'] - $data['pre_total_cost_of_sold'];


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year->first() : null;
        $data['fiscal_year'] = $fiscal_year->get()->pluck('title', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $data['report_title'] = "Balance Sheet <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");

//         $query = $this->authCompany($query, $request);

        $data['t_based_view'] = $request->t_based_view;


        if (
            $request->type == "print" ||
            $request->type == "download" ||
            $request->type == "full_balance_sheet" ||
            $request->type == "download_full_balance_sheet"
        ) {

            $data = $this->company($data);

            if ($request->type == "print") {
                if ($request->t_based_view)
                    return view('member.reports.balance_sheet.print-balance-sheet-T-table-only', $data);
                else
                    return view('member.reports.balance_sheet.print-balance-sheet-only', $data);
            } else if ($request->type == "download") {

                if ($request->t_based_view)
                    $pdf = PDF::loadView('member.reports.balance_sheet.print-balance-sheet-T-table-only', $data);
                else
                    $pdf = PDF::loadView('member.reports.balance_sheet.print-balance-sheet-only', $data);

                $file_name = file_name_generator("Balance_Sheet_Report_");

                return $pdf->download($file_name . ".pdf");
            } else if ($request->type == "full_balance_sheet") {
                return view('member.reports.balance_sheet.print-balance-sheet-details', $data);
            } else if ($request->type == "download_full_balance_sheet") {
                $pdf = PDF::loadView('member.reports.balance_sheet.print-balance-sheet-details', $data);
                $file_name = file_name_generator("Balance_Sheet_Report_");

                return $pdf->download($file_name . ".pdf");
            }

        } else {

            if ($request->t_based_view) {
                return view('member.reports.balance_sheet.balance_sheet_T_Table', $data);
            } else {
                return view('member.reports.balance_sheet.balance_sheet_report', $data);
            }
        }
    }


}

