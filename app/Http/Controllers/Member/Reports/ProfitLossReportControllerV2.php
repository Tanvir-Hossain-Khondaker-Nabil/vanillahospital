<?php

namespace App\Http\Controllers\Member\Reports;

use App\Http\Services\NetProfit;
use App\Models\AccountType;
use App\Models\Company;
use App\Models\FiscalYear;
use App\Models\PurchaseDetail;
use App\Models\SaleDetails;
use App\Models\StockReport;
use App\Models\Transactions;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitLossReportControllerV2 extends BaseReportController
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
        $data['trail_balance'] =  false;
        $request['returnData'] = true;

        $inventoryReport = new InventoryReportController();
        $data = $inventoryReport->index($request, $data);
        $data = $inventoryReport->sale_details($request, $data);
        $data = $inventoryReport->sale_return_details($request, $data);
        $data = $inventoryReport->purchase_details($request, $data);
        $data = $inventoryReport->purchase_return_details($request, $data);


        $data = $this->get_net_purchase($data);
        $data = $this->get_net_sale($data);
        $data = $this->get_total_ABC($data);

        $accountBalanceReport = new AccountBalanceReportControllerV2();
        $data = $accountBalanceReport->cost_of_sold_report($request, $data);
        $data = $accountBalanceReport->expense_report($request, $data);
        $data = $accountBalanceReport->income_report($request, $data);


        $data = $this->get_gross_profit($data);
        $data = $this->get_net_profit($request, $data);


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");


        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year : null;
        $data['fiscal_year'] = $fiscal_year->pluck('title', 'id');
        $data['fiscal_years'] = $fiscal_year->get();
        $data['t_based_view'] = $request->t_based_view;

        $data['report_title'] = trans('common.profit_and_loss')." <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data = $this->company($data);

        if ($request->type == "print" || $request->type == "download" || $request->type == "download_full_pl" || $request->type == "full_pl") {

            if ($request->type == "print") {

                if ($request->t_based_view)
                    return view('member.reports.profit_loss.print_profit_lost_T_table', $data);
                else
                    return view('member.reports.profit_loss.print_profit_lost_report_v2', $data);

            } else if ($request->type == "download") {

                if ($request->t_based_view)
                    $pdf = PDF::loadView('member.reports.profit_loss.print_profit_lost_T_table', $data);
                else
                    $pdf = PDF::loadView('member.reports.profit_loss.print_profit_lost_report_v2', $data);

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
                return view('member.reports.profit_loss.profit_lost_v2', $data);
        }
    }


    public function get_net_sale($data = [])
    {

        $data['net_sale'] = ($data['total_sales']-$data['total_sales_return']);
        $data['pre_net_sale'] = ($data['pre_total_sales']-$data['pre_total_sales_return']);


        return $data;
    }


    public function get_net_purchase($data = [])
    {

        $data['net_purchase'] = ($data['total_purchases']-$data['total_purchases_return']);
        $data['pre_net_purchase'] = ($data['pre_total_purchases']-$data['pre_total_purchases_return']);

        return $data;
    }


    public function get_total_ABC($data = [])
    {

        $data['total_AB'] = $data['openingStock']+$data['net_purchase'];
        $data['pre_total_AB'] = $data['pre_openingStock']+$data['pre_net_purchase'];

        $data['total_ABC'] = $data['total_AB']-$data['total_inventory'];
        $data['pre_total_ABC'] = $data['pre_total_AB']-$data['pre_total_inventory'];

        return $data;
    }


    public function get_gross_profit($data = [])
    {

        $data['gross_profit'] = $data['net_sale'] - $data['total_cost_of_sold'];
        $data['pre_gross_profit'] = $data['pre_net_sale'] - $data['pre_total_cost_of_sold'];


        return $data;
    }

    public function get_net_profit($request, $data = [])
    {

        $data['net_profit'] = $data['gross_profit'] + $data['total_incomes'] - $data['total_expenses'] ;
        $data['pre_net_profit'] = $data['pre_gross_profit'] + $data['pre_total_incomes'] - $data['pre_total_expenses'] ;


        if ( !empty($this->fromDate) && !empty($this->toDate) && Auth::user()->company   && Carbon::parse($this->toDate)->format("Y")>2019) {

            $net_profit = new NetProfit($this->fromDate, $this->toDate, $request->all(), $data['net_profit']);
            $net_profit->save();
        }


        return $data;
    }
}