<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Company;
use App\Models\FiscalYear;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitLossReportControllerV3 extends BaseReportController
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
        $data = $accountBalanceReport->cost_of_sold_report($request, $data);
        $data = $accountBalanceReport->expense_report($request, $data);
        $data = $accountBalanceReport->income_report($request, $data);

        $data['net_profit'] = ($data['total_sales'] + $data['total_incomes']) - $data['total_expenses'] - $data['total_cost_of_sold'];
        $data['pre_net_profit'] = ($data['pre_total_sales'] + $data['pre_total_incomes']) - $data['pre_total_expenses'] - $data['pre_total_cost_of_sold'];


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");


        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year->first() : null;
        $data['fiscal_year'] = $fiscal_year->pluck('title', 'id');
        $data['fiscal_years'] = $fiscal_year->get();
        $data['t_based_view'] = $request->t_based_view;

        $data['report_title'] = "Profit And Loss <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data = $this->company($data);

        if ($request->type == "print" || $request->type == "download" || $request->type == "download_full_pl" || $request->type == "full_pl") {

            if ($request->type == "print") {

                if ($request->t_based_view)
                    return view('member.reports.profit_loss.print_profit_lost_T_table', $data);
                else
                    return view('member.reports.profit_loss.print_profit_lost_report', $data);

            } else if ($request->type == "download") {

                if ($request->t_based_view)
                    $pdf = PDF::loadView('member.reports.profit_loss.print_profit_lost_T_table', $data);
                else
                    $pdf = PDF::loadView('member.reports.profit_loss.print_profit_lost_report', $data);

                $file_name = file_name_generator("profit_and_lost_Report_");

                return $pdf->download($file_name . ".pdf");
            } else if ($request->type == "full_pl") {
//                return view('member.reports.print_profit_lost_details', $data);
                return view('member.reports.profit_loss.print_profit_lost_details', $data);
            } else if ($request->type == "download_full_pl") {
                $pdf = PDF::loadView('member.reports.profit_loss.print_profit_lost_details', $data);
                $file_name = file_name_generator("profit_and_lost_Report_");

                return $pdf->download($file_name . ".pdf");
            }

        } else {

            if ($request->t_based_view)
                return view('member.reports.profit_loss.profit_lost_T_table', $data);
            else
                return view('member.reports.profit_loss.profit_lost', $data);
        }
    }
}
