<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\Company;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\StockReport;
use App\Models\SupplierOrCustomer;
use App\Models\Transactions;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitLossReportController extends BaseReportController
{
    public function index(Request $request)
    {

        if (isset(Auth::user()->company_id) && Auth::user()->company_id != null) {
            $request['company_id'] = Auth::user()->company_id;
        }

        $inventories = new StockReport();
        $inventories = $this->authCompany($inventories, $request);

        $inputs = $request->all();
        $fromDate = $toDate = '';

        if (!empty($inputs['from_date']) && !empty($inputs['to_date'])) {
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
            $inputs['to_date'] = $toDate = $date = db_date_format($inputs['to_date']);
        } else if (!empty($request->fiscal_year)) {

            $fiscal_year = FiscalYear::find($request->fiscal_year);
            $fromDate = $fiscal_year->start_date;
            $toDate = $date = $fiscal_year->end_date;
        } else if (!empty($request->year)) {

            $fromDate = $request->year . "-01-01";
            $in_date = $inventories->authCompany()->select('date')->orderby('date', 'desc')->whereYear('date', $request->year)->first();
            $last_date = Transactions::authCompany()->select('date')->orderby('date', 'desc')->whereYear('date', $request->year);
            $last_date = $this->authCompany($last_date, $request);
            $last_date = $last_date->first();
            $date = $in_date ? $in_date->date : null;
            $toDate = $last_date ? $last_date->date : null;
        } else {

            if (Auth::user()->company) {
                $set_company_fiscal_year = Auth::user()->company->fiscal_year->first();
                $fromDate = $set_company_fiscal_year->start_date;
                $toDate = $date = $set_company_fiscal_year->end_date;

            } else {

                $from_date = Transactions::authCompany()->select('date')->orderby('date', 'asc');
                $from_date = $this->authCompany($from_date, $request);
                $from_date = $from_date->first();
                $fromDate = $from_date ? $from_date->date : null;

                $last_date = Transactions::authCompany()->select('date')->orderby('date', 'desc');
                $last_date = $this->authCompany($last_date, $request);
                $last_date = $last_date->first();
                $toDate = $last_date ? $last_date->date : null;
            }

        }

        if (!$fromDate || !$toDate) {
            $status = ['type' => 'danger', 'message' => 'There is no data in that date/year for Balance Sheet'];
            return redirect()->back()->with('status', $status);
        }

        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        $pre_fromDate  = Transactions::authCompany()->select('date')->orderBy('date','asc')->first();
        $pre_fromDate = $data['pre_fromDate'] = $pre_fromDate->date;
        $pre_toDate = Carbon::parse($fromDate)->subDay(1);
        $data['pre_toDate'] = $pre_toDate  = $pre_toDate->toDateString();


        $stock_product = StockReport::whereBetween('date', [$fromDate, $toDate]);
        $stock_product = $this->authCompany($stock_product, $request);
        $stock_product = $stock_product->orderBy('item_id', 'asc')->distinct('item_id')->select('item_id')->pluck('item_id')->toArray();

        $price = $inventory_price = $pre_price = $pre_inventory_price = 0;
        $closingInventories = [];
        $openingInventories = [];
        $last_category = '';
        foreach ($stock_product as $key => $value)
        {
            $stock = StockReport::where('item_id', $value);
            $stock = $this->authCompany($stock, $request);

            $closing = $stock->where('date', '<=', $toDate)->orderBy('date', 'desc')->first();

            $opening = $stock->where('date', '<=', $fromDate)->orderBy('date', 'asc')->first();

            $request['stock_from_date'] = $fromDate;
            $request['stock_to_date'] = $toDate;
            $openingInventories[] = $opening;

            $close_stock_report = StockReport::whereDate('date', '<=' ,$toDate)->where('item_id', $value)->orderBy('date', 'desc')->first();
            $open_stock_report = StockReport::whereDate('date', '>=' ,$fromDate)->where('item_id', $value)->orderBy('date', 'asc')->first();
            $pre_close_stock_report = StockReport::whereDate('date', '<=' ,$fromDate)->where('item_id', $value)->orderBy('date', 'asc')->first();

            $closing['close_qty'] = $close_qty = $close_stock_report ? ($close_stock_report->opening_stock + $close_stock_report->purchase_qty - $close_stock_report->purchase_return_qty - $close_stock_report->sale_qty + $close_stock_report->sale_return_qty) : 0;

            $closing['open_qty']= $open_qty = $open_stock_report ?  $open_stock_report->opening_stock : 0;

            $closing['previous_open_qty'] = $previous_open_qty = $pre_close_stock_report ? $pre_close_stock_report->opening_stock : 0;
            $closing['previous_close_qty'] = $previous_close_qty = $open_stock_report ? $open_stock_report->opening_stock : 0;

            $item_price = $this->countStockPrice([], $request, $value);

            if (empty($item_price['item_price'])) {
                $item_qty_price = 0;
            } else {
                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
            }

            if( $key == 0 || $last_category != $closing->item->category->display_name)
            {
//                $data['total_category_qty'] = isset($total_category_qty) ? $total_category_qty : 0;
//                $data['pre_total_category_qty'] = isset($pre_total_category_qty) ? $pre_total_category_qty : 0;
//                $total_category_qty = $pre_total_category_qty =  0;
                $total_per_category = $pre_total_per_category = 0;

            }

            $price += $opening ? ($opening->opening_stock * $item_qty_price) : 0;
            $closing['qty'] = $qty = $closing->opening_stock + $closing->purchase_qty - $closing->purchase_return_qty - $closing->sale_qty + $closing->sale_return_qty;
            $closing['category'] = $last_category = $closing->item->category->display_name;
            $closing['unit'] = $closing->item->unit;
            $closing['item_price'] = $item_qty_price;

            $inventory_price +=  $qty * $item_qty_price;
            $total_per_category += $qty*$item_qty_price;

            $closing['total_per_category'] = $total_per_category;
            $closing['pre_total_per_category']  = $pre_total_per_category;


            $pre_price += ($previous_open_qty * $item_qty_price);
            $request['stock_from_date'] = $pre_fromDate;
            $request['stock_to_date'] = $pre_toDate;
            $item_price = $this->countStockPrice([], $request, $value);

            if (empty($item_price['item_price'])) {
                $item_qty_price = 0;
            } else {
                $item_qty_price = create_float_format($item_price['item_price']['price_qty']);
            }


            $closing['pre_item_price'] = $item_qty_price;
            $closing['pre_qty'] = $previous_close_qty;
            $pre_inventory_price += $previous_close_qty * $item_qty_price;
            $pre_total_per_category += $previous_close_qty*$item_qty_price;

            if(
                $qty > 0
                || $close_qty > 0
                || $total_per_category > 0
                || $pre_total_per_category > 0
            )
            {
                $closingInventories[$value] = $closing;
            }


        }


//        $expect = ['opening_stock', 'company_id', 'product_code', 'sale_qty', 'created_at', 'updated_at', 'purchase_return_qty', 'sale_return_qty', 'ProductDateID'];
        $data['inventories'] = collect($closingInventories)->sortBy('category');

        $data['total_inventory'] = $total_inventory = create_float_format($inventory_price,2);
        $data['openingStock'] = $openingStock = $price;

        $data['pre_total_inventory'] = $pre_total_inventory = $pre_inventory_price;
        $data['pre_openingStock'] = $pre_openingStock = $pre_price;


        $sale = AccountType::where('name', '=', "sales")->select('id')->first();

        $data_sale = $sale->account_head_balance();
        $data_sale = $pre_sale = $this->authCompany($data_sale, $request);
        $data_sale = $data_sale->latestAccountBalance($toDate)->first();
        $pre_sale = $pre_sale->previousAccountBalance($fromDate)->first();


        $data['sales'] = $data_sale ? $data_sale->balance * (-1) : 0;
        $data['pre_sales'] = $pre_sale ? $pre_sale->balance * (-1) : 0;

        $sales = $sale_details = SaleDetails::whereBetween('date', [$fromDate, $toDate]);
        $sales = $this->authCompany($sales, $request);
        $data['total_sales'] = $sales->sum('total_price');

        $pre_sales = $pre_sale_details = SaleDetails::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_sales = $this->authCompany($pre_sales, $request);
        $data['pre_total_sales'] = $pre_sales->sum('total_price');
        $sale_details = $this->authCompany($sale_details, $request);
        $pre_sale_details = $this->authCompany($pre_sale_details, $request);

        $data['sale_details'] = $sale_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();

        $data['pre_sale_details'] = $pre_sale_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();


        $purchases = $purchase_details = PurchaseDetail::whereBetween('date', [$fromDate, $toDate]);
        $purchases = $this->authCompany($purchases, $request);
        $data['total_purchases'] = $purchases->sum('total_price');

        $pre_purchases = $pre_purchase_details = PurchaseDetail::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_purchases = $this->authCompany($pre_purchases, $request);
        $data['pre_total_purchases'] = $pre_purchases->sum('total_price');

        $purchase_details = $this->authCompany($purchase_details, $request);
        $data['purchase_details'] = $purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();

        $pre_purchase_details = $this->authCompany($pre_purchase_details, $request);
        $data['pre_purchase_details'] = $pre_purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->get();


        $data['total_liabilities_expenses'] = 0;

        $cost_of_sold = AccountType::where('name', '=', "cost_of_goods_sold")->select('id')->first();
        $cost_of_sold_items = AccountType::where('parent_id', $cost_of_sold->id)->select('id')->get();

        $cost_of_sold_balance = $cost_of_sold->account_head_balance();
        $cost_of_sold_balance = $pre_cost_of_sold_balance = $cost_of_sold_balance->authCompany($cost_of_sold_balance, $request);
        $cost_of_sold_balance = $cost_of_sold_balance->latestAccountBalance($toDate)->first();
        $pre_cost_of_sold_balance = $pre_cost_of_sold_balance->previousAccountBalance($toDate)->first();

        $data['cost_of_sold_balance'] = $cost_of_sold_balance ? $cost_of_sold_balance->balance-($pre_cost_of_sold_balance ? $pre_cost_of_sold_balance->balance : 0) : 0;
        $data['pre_cost_of_sold_balance'] = $pre_cost_of_sold_balance ? $pre_cost_of_sold_balance->balance : 0;

        $total_cost_of_sold = $openingStock + $data['total_purchases'] - $total_inventory + $data['cost_of_sold_balance'];
        $pre_total_cost_of_sold = $pre_openingStock + $data['pre_total_purchases'] - $pre_total_inventory + $data['pre_cost_of_sold_balance'];

        $data['cost_of_sold_items'] = [];
        foreach ($cost_of_sold_items as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['cost_of_sold_items'][$key]['account_type_id'] = $value->id;
                $data['cost_of_sold_items'][$key]['account_type_name'] = $query->account_types->display_name;
                $data['cost_of_sold_items'][$key]['balance'] = $query->balance;
                $total_cost_of_sold += $query->balance;

                $data['cost_of_sold_items'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $pre_total_cost_of_sold += $previous_query ? $previous_query->balance : 0;
            }
        }

        $data['total_cost_of_sold'] = $total_cost_of_sold;
        $data['pre_total_cost_of_sold'] = $pre_total_cost_of_sold;

        $expense = AccountType::where('name', '=', "expenses")->select('id')->first();
        $expenses = AccountType::where('parent_id', $expense->id)->select('id', 'display_name')->get();

        $ex_balance = $expense->account_head_balance();
        $ex_balance = $pre_ex_balance = $ex_balance->authCompany($ex_balance, $request);
        $ex_balance = $ex_balance->latestAccountBalance($toDate)->first();
        $pre_ex_balance = $pre_ex_balance->previousAccountBalance($fromDate)->first();
        $data['ex_balance'] = $data['total_expenses'] = $ex_balance ? $ex_balance->balance : 0;
        $data['pre_ex_balance'] = $data['pre_total_expenses'] = $pre_ex_balance ? $pre_ex_balance->balance : 0;

        $data['expenses'] = [];

        foreach ($expenses as $key => $value) {
            $child_expenses = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();

            if (count($child_expenses) > 0) {
                $query = $value->account_head_balance()->latestAccountBalance($toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                $data['expenses'][$value->id]['parent'] = "yes";
                $data['expenses'][$value->id]['account_type_id'] = $value->id;
                $data['expenses'][$value->id]['account_type_name'] = $value->display_name;
                $data['expenses'][$value->id]['balance'] = $query ? $query->balance : 0;
                $data['total_expenses'] += $query ? $query->balance : 0 ;

                $data['expenses'][$value->id]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_expenses'] += $previous_query ? $previous_query->balance : 0 ;

                foreach ($child_expenses as $value2) {
                    $query2 = $value2->account_head_balance()->latestAccountBalance($toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value->account_head_balance()->previousAccountBalance($fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();


                    if ($query2) {
                        $data['expenses'][$value2->id]['child'] = "yes";
                        $data['expenses'][$value2->id]['account_type_id'] = $value2->id;
                        $data['expenses'][$value2->id]['account_type_name'] = $value2->display_name;
                        $data['expenses'][$value2->id]['balance'] = $query2 ? $query2->balance : 0;
                        $data['total_expenses'] += $query2 ? $query2->balance : 0;


                        $data['expenses'][$value2->id]['pre_balance'] = $previous_query2 ? $previous_query2->balance : 0;
                        $data['pre_total_expenses'] += $previous_query2 ? $previous_query2->balance : 0 ;
                    }
                }


            } else {

                $query = $value->account_head_balance()->latestAccountBalance($toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                if ($query) {
                    $data['expenses'][$value->id]['account_type_id'] = $value->id;
                    $data['expenses'][$value->id]['account_type_name'] = $value->display_name;
                    $data['expenses'][$value->id]['balance'] = $query ? $query->balance : 0;
                    $data['total_expenses'] += $query ? $query->balance : 0 ;


                    $data['expenses'][$value->id]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                    $data['pre_total_expenses'] += $previous_query ? $previous_query->balance : 0 ;
                }
            }

        }


        $income = AccountType::where('name', '=', "other_revenue")->select('id', 'display_name')->first();
        $incomes = AccountType::where('parent_id', $income->id);
        $incomes = $incomes->authCompany($incomes, $request)->select('id', 'display_name')->get();

        $in_balance = $income->account_head_balance();
        $in_balance = $pre_in_balance = $in_balance->authCompany($in_balance, $request);
        $in_balance = $in_balance->latestAccountBalance($toDate)->first();
        $pre_in_balance = $pre_in_balance->previousAccountBalance($fromDate)->first();


        $data['in_balance'] = $data['total_incomes'] = $in_balance ? (-1)*$in_balance->balance : 0;
        $data['pre_total_incomes'] = $pre_in_balance ? (-1)*$pre_in_balance->balance : 0;
        $data['incomes'] = [];

        foreach ($incomes as $key => $value) {
            $child_incomes = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();
            if (count($child_incomes) > 1) {
                foreach ($child_incomes as $value2) {

                    $query = $value->account_head_balance()->latestAccountBalance($toDate);
                    $query = $this->authCompany($query, $request);
                    $query = $query->first();

                    $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                    $previous_query = $this->authCompany($previous_query, $request);
                    $previous_query = $previous_query->first();


                    $data['incomes'][$value->id]['parent'] = "yes";
                    $data['incomes'][$value->id]['account_type_id'] = $value->id;
                    $data['incomes'][$value->id]['account_type_name'] = $value->display_name;
                    $data['incomes'][$value->id]['balance'] = (-1)*$query->balance;
                    $data['total_incomes'] += (-1)*$query->balance;

                    $data['incomes'][$value->id]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                    $data['pre_total_incomes'] += $previous_query ? (-1)*$previous_query->balance : $previous_query;

                    $query2 = $value2->account_head_balance()->latestAccountBalance($toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value->account_head_balance()->previousAccountBalance($fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();


                    if ($query2) {
                        $data['incomes'][$value2->id]['child'] = "yes";
                        $data['incomes'][$value2->id]['account_type_id'] = $value2->id;
                        $data['incomes'][$value2->id]['account_type_name'] = $value2->display_name;
                        $data['incomes'][$value2->id]['balance'] = (-1)*$query2->balance;
                        $data['total_incomes'] += (-1)*$query2->balance;

                        $data['incomes'][$value2->id]['pre_balance'] = $previous_query2 ? (-1)*$previous_query2->balance : 0;
                        $data['pre_total_incomes'] += $previous_query2 ? (-1)*$previous_query2->balance : 0;
                    }
                }
            } else {

                $query = $value->account_head_balance()->latestAccountBalance($toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();


                if ($query) {
                    $data['incomes'][$value->id]['account_type_id'] =  $value->id;
                    $data['incomes'][$value->id]['account_type_name'] = $value->display_name;
                    $data['incomes'][$value->id]['balance'] = (-1)*$query->balance;
                    $data['total_incomes'] += (-1)*$query->balance;

                    $data['incomes'][$value->id]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                    $data['pre_total_incomes'] += $previous_query ? (-1)*$previous_query->balance : 0;
                }
            }
        }

        $data['total_incomes'] -= $data['pre_total_incomes'];
        $data['total_expenses'] -= $data['pre_total_expenses'];

        $data['net_profit'] = ($data['total_sales'] + $data['total_incomes']) - $data['total_expenses'] - $total_cost_of_sold;
        $data['pre_net_profit'] = ($data['pre_total_sales'] + $data['pre_total_incomes']) - $data['pre_total_expenses'] - $pre_total_cost_of_sold;


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");


        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year->first() : null;
        $data['fiscal_year'] = $fiscal_year->pluck('title', 'id');
        $data['fiscal_years'] = $fiscal_year->get();
        $data['t_based_view'] = $request->t_based_view;

        $data['report_title'] = "Profit And Loss <br/> " . db_date_month_year_format($fromDate) . ($toDate ? " to " . db_date_month_year_format($toDate) : "");
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