<?php

namespace App\Http\Controllers\Member\Reports;

use App\Http\ECH\SqlCodeGenerator;
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

class BalanceSheetController extends BaseReportController
{
    public function index(Request $request)
    {

        if (isset(Auth::user()->company_id) && Auth::user()->company_id != null) {
            $request['company_id'] = Auth::user()->company_id;
        }

        $inventories = new StockReport();
//        $table = new SqlCodeGenerator($inventories);
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
            $in_date = $inventories->select('date')->orderby('date', 'desc')->whereYear('date', $request->year)->first();
            $last_date = Transactions::select('date')->orderby('date', 'desc')->whereYear('date', $request->year);
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

                $from_date = Transactions::select('date')->orderby('date', 'asc');
                $from_date = $this->authCompany($from_date, $request);
                $from_date = $from_date->first();
                $fromDate = $from_date ? $from_date->date : null;

                $last_date = Transactions::select('date')->orderby('date', 'desc');
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

            $closing = $stock->where('date', '<=', $toDate)
                ->orderBy('date', 'desc')->first();

            $opening = $stock->where('date', '<=', $fromDate)
                ->orderBy('date', 'asc')->first();

            $request['stock_from_date'] = $fromDate;
            $request['stock_to_date'] = $toDate;
            $openingInventories[] = $opening;

            $close_stock_report = StockReport::whereDate('date', '<=' ,$toDate)
                ->where('item_id', $value)->orderBy('date', 'desc')->first();
            $open_stock_report = StockReport::whereDate('date', '>=' ,$fromDate)
                ->where('item_id', $value)->orderBy('date', 'asc')->first();
            $pre_close_stock_report = StockReport::whereDate('date', '<=' ,$fromDate)
                ->where('item_id', $value)->orderBy('date', 'asc')->first();

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

            if( $qty > 0 || $close_qty > 0)
            {
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

//                if(
//                    $qty > 0
//                    || $close_qty > 0
//                    || $total_per_category > 0
//                    || $pre_total_per_category > 0
//                )
//                {
                $closingInventories[$value] = $closing;
//                }
            }


        }


//        $expect = ['opening_stock', 'company_id', 'product_code', 'sale_qty', 'created_at', 'updated_at', 'purchase_return_qty', 'sale_return_qty', 'ProductDateID'];
        $data['inventories'] = collect($closingInventories)->sortBy('category');
//        $data['inventories'] = $closingInventories;
        $data['total_inventory'] = $total_inventory = create_float_format($inventory_price,2);
        $data['openingStock'] = $openingStock = $price;

        $data['pre_total_inventory'] = $pre_total_inventory = $pre_inventory_price;
        $data['pre_openingStock'] = $pre_openingStock = $pre_price;

        $fixed_asset = AccountType::where('name', '=', "fixed_assets")->select('id')->first();
        $fixed_assets = AccountType::where('parent_id', $fixed_asset->id)->select('id', 'display_name')->get();

        $fixed_asset_balance = $fixed_asset->account_head_balance();
        $fixed_asset_balance= $fixed_asset_pre_balance = $this->authCompany($fixed_asset_balance, $request);
        $data['fixed_asset'] = $fixed_asset_balance = $fixed_asset_balance->latestAccountBalance($toDate)->first();
        $data['pre_fixed_asset'] = $fixed_asset_pre_balance = $fixed_asset_pre_balance->previousAccountBalance($fromDate)->first();

        $data['total_fixed'] = $fixed_asset_balance ? $fixed_asset_balance->balance : 0;
        $data['pre_total_fixed'] = $fixed_asset_pre_balance ? $fixed_asset_pre_balance->balance : 0;

        $data['fixed_assets'] = [];
        foreach ($fixed_assets as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['fixed_assets'][$key]['account_type_id'] = $value->id;
                $data['fixed_assets'][$key]['account_type_name'] = $value->display_name;
                $data['fixed_assets'][$key]['pre_balance'] = $pre_balance = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_fixed'] += $pre_balance;

                $balance = $query->balance-$pre_balance;
                $data['fixed_assets'][$key]['balance'] = $balance;
                $data['total_fixed'] += $query->balance;

            }
        }

        $current_asset = AccountType::where('name', '=', "current_assets")->select('id')->first();
        $current_assets = AccountType::whereNotIn('name', ['cash', 'bank', 'advance_deposits&_prepayments', 'due_from_affiliated_company', 'fixed_deposits_receipts'])->where('parent_id', $current_asset->id)->select('id', 'display_name')->get();

        $current_asset_balance = $current_asset->account_head_balance();
        $current_asset_balance = $this->authCompany($current_asset_balance, $request);
        $data_current_asset = $current_asset_balance->latestAccountBalance($toDate)->first();
        $pre_data_current_asset = $current_asset_balance->previousAccountBalance($fromDate)->first();

        $data['current_asset_balance'] = $data['total_current_asset'] = $data_current_asset ? $data_current_asset->balance : 0;
        $data['pre_current_asset_balance'] = $data['pre_total_current_asset'] = $pre_data_current_asset ? $pre_data_current_asset->balance : 0;

        $data['current_assets'] = [];

        foreach ($current_assets as $key => $value) {

            $child_current_assets = AccountType::where('parent_id', $value->id)->select('id', 'display_name')->get();

            if (count($child_current_assets) > 0) {

                $query = $value->account_head_balance()->latestAccountBalance($toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                $data['current_assets'][$value->id]['parent'] = "yes";
                $data['current_assets'][$value->id]['account_type_id'] = $value->id;
                $data['current_assets'][$value->id]['account_type_name'] = $value->display_name;
                $data['current_assets'][$value->id]['balance'] = $query ? $query->balance : 0;
                $data['total_current_asset'] +=  $query ? $query->balance : 0;

                $data['current_assets'][$value->id]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_current_asset'] += $previous_query ? $previous_query->balance : 0;

                foreach ($child_current_assets as $value2) {
                    $query2 = $value2->account_head_balance()->latestAccountBalance($toDate);
                    $query2 = $this->authCompany($query2, $request);
                    $query2 = $query2->first();

                    $previous_query2 = $value2->account_head_balance()->previousAccountBalance($fromDate);
                    $previous_query2 = $this->authCompany($previous_query2, $request);
                    $previous_query2 = $previous_query2->first();

                    if ($query2) {
                        $data['current_assets'][$value2->id]['child'] = "yes";
                        $data['current_assets'][$value2->id]['account_type_id'] = $value->id2;
                        $data['current_assets'][$value2->id]['account_type_name'] = $value2->display_name;
                        $data['current_assets'][$value2->id]['balance'] = $query2->balance;
                        $data['total_current_asset'] += $query2->balance;
                        $data['current_assets'][$value2->id]['pre_balance'] = $previous_query2 ? $previous_query2->balance : 0;
                        $data['pre_total_current_asset'] += $previous_query2 ? $previous_query2->balance : 0;
                    }
                }

            }else{

                $query = $value->account_head_balance()->latestAccountBalance($toDate);
                $query = $this->authCompany($query, $request);
                $query = $query->first();

                $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
                $previous_query = $this->authCompany($previous_query, $request);
                $previous_query = $previous_query->first();

                if ($query) {
                    $data['current_assets'][$value->id]['account_type_id'] = $value->id;
                    $data['current_assets'][$value->id]['account_type_name'] = $value->display_name;
                    $data['current_assets'][$value->id]['balance'] = $query->balance;
                    $data['current_assets'][$value->id]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                    $data['total_current_asset'] += $query->balance;
                    $data['pre_total_current_asset'] += $previous_query ? $previous_query->balance : 0;
                }
            }

        }


        $advance_prepayment = AccountType::where('name', '=', "advance_deposits&_prepayments")->select('id')->first();
        $advance_prepayments = AccountType::where('parent_id', $advance_prepayment->id)->select('id', 'display_name')->get();

        $advance_prepayment_balance = $advance_prepayment->account_head_balance();
        $advance_prepayment_balance = $this->authCompany($advance_prepayment_balance, $request);
        $data_advance_prepayment_balance = $advance_prepayment_balance->latestAccountBalance($toDate)->first();
        $pre_data_advance_prepayment_balance = $advance_prepayment_balance->previousAccountBalance($fromDate)->first();

        $data['total_advance_prepayment'] = $data_advance_prepayment_balance ? $data_advance_prepayment_balance->balance : 0;
        $data['pre_total_advance_prepayment'] = $pre_data_advance_prepayment_balance ? $pre_data_advance_prepayment_balance->balance : 0;

        $data['advance_prepayments'] = [];
        foreach ($advance_prepayments as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['advance_prepayments'][$key]['account_type_id'] = $value->id;
                $data['advance_prepayments'][$key]['account_type_name'] = $value->display_name;
                $data['advance_prepayments'][$key]['balance'] = $query->balance;
                $data['total_advance_prepayment'] += $query->balance;

                $data['advance_prepayments'][$key]['pre_balance'] = $previous_query ? $previous_query->balance :  0;
                $data['pre_total_advance_prepayment'] += $previous_query ? $previous_query->balance : 0;
            }
        }

        $fixed_deposits_receipt = AccountType::where('name', '=', "fixed_deposits_receipts")->select('id')->first();
        $fixed_deposits_receipts = AccountType::where('parent_id', $fixed_deposits_receipt->id)->select('id', 'display_name')->get();

        $fixed_deposits_receipt_balance = $fixed_deposits_receipt->account_head_balance();
        $fixed_deposits_receipt_balance = $this->authCompany($fixed_deposits_receipt_balance, $request);
        $data_fixed_deposits_receipt_balance = $fixed_deposits_receipt_balance->latestAccountBalance($toDate)->first();
        $pre_fixed_deposits_receipt_balance = $fixed_deposits_receipt_balance->previousAccountBalance($fromDate)->first();

        $data['total_fixed_deposits_receipt'] = $data_fixed_deposits_receipt_balance ? $data_fixed_deposits_receipt_balance->balance : 0;
        $data['pre_total_fixed_deposits_receipt'] = $pre_fixed_deposits_receipt_balance ? $pre_fixed_deposits_receipt_balance->balance : 0;

        $data['fixed_deposits_receipts'] = [];
        foreach ($fixed_deposits_receipts as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['fixed_deposits_receipts'][$key]['account_type_id'] = $value->id;
                $data['fixed_deposits_receipts'][$key]['account_type_name'] = $value->display_name;
                $data['fixed_deposits_receipts'][$key]['balance'] = $query->balance;
                $data['total_fixed_deposits_receipt'] += $query->balance;

                $data['fixed_deposits_receipts'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_fixed_deposits_receipt'] += $previous_query ? $previous_query->balance : 0;
            }
        }


        $due_from_affiliated_company = AccountType::where('name', '=', "due_from_affiliated_company")->select('id')->first();
        $due_from_affiliated_companies = AccountType::where('parent_id', $due_from_affiliated_company->id)->select('id', 'display_name')->get();

        $due_from_affiliated_company_balance = $due_from_affiliated_company->account_head_balance();
        $due_from_affiliated_company_balance = $this->authCompany($due_from_affiliated_company_balance, $request);
        $data_due_from_affiliated_company_balance = $due_from_affiliated_company_balance->latestAccountBalance($toDate)->first();
        $pre_due_from_affiliated_company_balance = $due_from_affiliated_company_balance->previousAccountBalance($fromDate)->first();

        $data['total_due_affiliated_company'] = $data_due_from_affiliated_company_balance ? $data_due_from_affiliated_company_balance->balance : 0;
        $data['pre_total_due_affiliated_company'] = $pre_due_from_affiliated_company_balance ? $pre_due_from_affiliated_company_balance->balance : 0;

        $data['due_companies'] = [];
        foreach ($due_from_affiliated_companies as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['due_companies'][$key]['account_type_id'] = $value->id;
                $data['due_companies'][$key]['account_type_name'] = $value->display_name;
                $data['due_companies'][$key]['balance'] = $query->balance;
                $data['total_due_affiliated_company'] += $query->balance;

                $data['due_companies'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_due_affiliated_company'] += $previous_query ? $previous_query->balance : 0;
            }
        }

        $data['total_cash_bank'] = $data['total_over_bank'] = 0;
        $data['pre_total_cash_bank'] = $data['pre_total_over_bank'] = 0;
        $data['cash_banks'] = $data['over_banks'] = [];

        $cash_bank_account = AccountType::authCompany()->whereIn('name', ['cash', 'bank'])->pluck('id')->toArray();
        $cash_banks = AccountType::whereIn('id', [3,4])->orwhereIntegerInRaw('parent_id', $cash_bank_account);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->select('id')->orderBy('id', 'asc')->get();



        foreach ($cash_banks as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                if ($query->balance != 0) {
                    $data['cash_banks'][$key]['account_type_id'] = $value->id;
                    $data['cash_banks'][$key]['account_type_name'] = $value->display_name;
                    $data['cash_banks'][$key]['balance'] = $query->balance;
                    $data['total_cash_bank'] += $query->balance;

                    $data['cash_banks'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                    $data['pre_total_cash_bank'] += $previous_query ? $previous_query->balance : 0;
                }
            }
        }


        $over_bank_account = AccountType::authCompany()->where('name', 'current_liabilities')->pluck('id')->toArray();
        $over_bank = AccountType::whereIntegerInRaw('parent_id', $over_bank_account)->pluck('id')->toArray();
        $over_banks = CashOrBankAccount::whereIntegerInRaw('account_type_id', $over_bank);
        $over_banks = $this->authCompany($over_banks, $request);
        $over_banks = $over_banks->select('id', 'account_type_id')->orderBy('id', 'asc')->get();


        foreach ($over_banks as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                if ($query->balance != 0) {
                    $data['over_banks'][$key]['account_type_id'] = $value->id;
                    $data['over_banks'][$key]['account_type_name'] = $value->display_name;
                    $data['over_banks'][$key]['balance'] = (-1)*$query->balance;
                    $data['total_over_bank'] += (-1)*$query->balance;

                    $data['over_banks'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                    $data['pre_total_over_bank'] += $previous_query ? (-1)*$previous_query->balance : 0;
                }
            }
        }


        // Only Customer are Trade Debtors
        // who have Debit balance must be bigger than 0;
        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $customers = $this->authCompany($supplier_or_customers, $request);
        $customers = $customers->where('customer_type', 'customer')->select('id', 'account_type_id')->get();

        $data['trade_debtors'] = $data['sundry_creditors'] = [];
        $data['total_trade_debtor'] = $data['total_sundry_creditors'] = 0;
        $data['pre_total_trade_debtor'] = $data['pre_total_sundry_creditors'] = 0;

        foreach ($customers as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();

            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                // Note: Change on Request Azim company
                if ($query->balance != 0) {
                    $data['trade_debtors'][$key]['account_type_id'] = $value->id;
                    $data['trade_debtors'][$key]['account_type_name'] = $value->display_name;
                    $data['trade_debtors'][$key]['balance'] = $query->balance;
                    $data['total_trade_debtor'] += $query->balance;

                    $data['trade_debtors'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                    $data['pre_total_trade_debtor'] += $previous_query ? $previous_query->balance : 0;
                }


                // Previous One
//                if ($query->balance > 0) {
//                    $data['trade_debtors'][$key]['account_type_id'] = $value->id;
//                    $data['trade_debtors'][$key]['account_type_name'] = $value->display_name;
//                    $data['trade_debtors'][$key]['balance'] = $query->balance;
//                    $data['total_trade_debtor'] += $query->balance;
//                } else {
//                    $data['sundry_creditors'][$key]['account_type_id'] = $value->id;
//                    $data['sundry_creditors'][$key]['account_type_name'] = $value->display_name;
//                    $data['sundry_creditors'][$key]['balance'] = $query->balance;
//                    $data['total_sundry_creditors'] += $query->balance;
//
//                }
            }
        }

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('account_type_id');
        $suppliers = $this->authCompany($supplier_or_customers, $request);
        $sundry_creditors = $suppliers->onlySuppliers()->select('id', 'account_type_id')->get();

        foreach ($sundry_creditors as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {

                // Note: Change on Request Azim company
                if ($query->balance != 0) {
                    $data['sundry_creditors'][$key]['account_type_id'] = $value->id;
                    $data['sundry_creditors'][$key]['account_type_name'] = $value->display_name;
                    $data['sundry_creditors'][$key]['balance'] = (-1)*$query->balance;
                    $data['total_sundry_creditors'] += (-1)*$query->balance;


                    $data['sundry_creditors'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                    $data['pre_total_sundry_creditors'] += $previous_query ? (-1)*$previous_query->balance : 0;
                }


                // Previous One
//                if ($query->balance < 0) {
//                    $data['account_payables'][$key]['account_type_id'] = $value->id;
//                    $data['account_payables'][$key]['account_type_name'] = $value->display_name;
//                    $data['account_payables'][$key]['balance'] = $query->balance;
//                    $data['total_account_payables'] += $query->balance;
//                } else {
//                    $data['account_receivables'][$key]['account_type_id'] = $value->id;
//                    $data['account_receivables'][$key]['account_type_name'] = $value->display_name;
//                    $data['account_receivables'][$key]['balance'] = $query->balance;
//                    $data['total_account_receivables'] += $query->balance;
//                }
            }

        }

        $data['account_payables'] = [];
        $data['account_receivables'] = [];
        $data['total_account_payables'] = $data['total_account_receivables'] = 0;
        $data['pre_total_account_payables'] = $data['pre_total_account_receivables'] = 0;

        $accounts_payable = AccountType::where('name', '=', "accounts_payable")->select('id')->first();
        $accounts_payables = AccountType::where('parent_id', $accounts_payable->id)->select('id', 'display_name')->get();

        foreach ($accounts_payables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query)
            {
                $data['account_payables'][$key]['account_type_id'] = $value->id;
                $data['account_payables'][$key]['account_type_name'] = $value->display_name;
                $data['account_payables'][$key]['balance'] = (-1)*$query->balance;
                $data['total_account_payables'] += (-1)*$query->balance;
                $data['account_payables'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_account_payables'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }

        }

        $accounts_receivable = AccountType::where('name', '=', "accounts_receivable")->select('id')->first();
        $accounts_receivables = AccountType::where('parent_id', $accounts_receivable->id)->select('id', 'display_name')->get();

        foreach ($accounts_receivables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query)
            {
                $data['account_receivables'][$key]['account_type_id'] = $value->id;                   $data['account_receivables'][$key]['account_type_name'] = $value->display_name;
                $data['account_receivables'][$key]['balance'] = $query->balance;
                $data['total_account_receivables'] += $query->balance;
                $data['account_receivables'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_total_account_receivables'] += $previous_query ? $previous_query->balance : 0;
            }

        }


        $equity = AccountType::where('name', '=', "equity")->select('id')->first();
        $equities = AccountType::where('parent_id', $equity->id)->select('id', 'display_name')->get();

        $equity_balance = $equity->account_head_balance();
        $equity_balance = $this->authCompany($equity_balance, $request);
        $data_equity = $equity_balance->latestAccountBalance($toDate)->first();
        $pre_equity = $equity_balance->previousAccountBalance($toDate)->first();

        $data['equity_balance'] = $data_equity ? $data_equity->balance : 0;
        $data['pre_equity_balance'] = $pre_equity ? $pre_equity->balance : 0;

        $data['equities'] = [];
        foreach ($equities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query) {
                $data['equities'][$key]['account_type_id'] = $value->id;
                $data['equities'][$key]['account_type_name'] = $value->display_name;
                $data['equities'][$key]['balance'] = $query->balance;
                $data['equity_balance'] += $query->balance;

                $data['equities'][$key]['pre_balance'] = $previous_query ? $previous_query->balance : 0;
                $data['pre_equity_balance']  += $previous_query ? $previous_query->balance : 0;
            }
        }


        $non_current_liability = AccountType::where('name', '=', "long-term_liabilities")->select('id')->first();
        $non_current_liabilities = AccountType::where('parent_id', $non_current_liability->id)->select('id', 'display_name')->get();


        $data_non_current_liability = $non_current_liability->account_head_balance();
        $data_non_current_liability = $pre_non_current_liability = $this->authCompany($data_non_current_liability, $request);
        $data_non_current_liability = $data_non_current_liability->latestAccountBalance($toDate)->first();
        $pre_non_current_liability = $pre_non_current_liability->previousAccountBalance($fromDate)->first();

        $data['non_current_liability_balance'] = $data['total_non_current_liability'] = $data_non_current_liability ? (-1)*$data_non_current_liability->balance : 0;
        $data['pre_non_current_liability_balance'] =  $data['pre_total_non_current_liability'] = $pre_non_current_liability ? (-1)*$pre_non_current_liability->balance : 0;

        $data['non_current_liabilities'] = [];
        foreach ($non_current_liabilities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['non_current_liabilities'][$key]['account_type_id'] = $value->id;
                $data['non_current_liabilities'][$key]['account_type_name'] = $value->display_name;
                $data['non_current_liabilities'][$key]['balance'] = (-1)*$query->balance;
                $data['total_non_current_liability'] += (-1)*$query->balance;

                $data['pre_non_current_liabilities'][$key]['balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_non_current_liability'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }
        }

        $current_liability = AccountType::where('name', '=', "current_liabilities")->select('id')->first();
        $current_liabilities = AccountType::whereNotIn('name', [ 'due_to_affiliated_company', 'liabilities_for_expenses' ,'income_tax_payable'])->where('parent_id', $current_liability->id)->select('id', 'display_name')->get();

        $data_current_liability = $current_liability->account_head_balance();
        $data_current_liability = $pre_current_liability = $this->authCompany($data_current_liability, $request);
        $data_current_liability = $data_current_liability->latestAccountBalance($toDate)->first();
        $pre_current_liability = $pre_current_liability->previousAccountBalance($fromDate)->first();

        $data['current_liability_balance'] = $data['total_current_liability'] = $data_current_liability ? (-1)*$data_current_liability->balance : 0;
        $data['pre_current_liability_balance'] = $data['pre_total_current_liability'] = $pre_current_liability ? (-1)*$pre_current_liability->balance : 0;

        $data['current_liabilities'] = [];
        foreach ($current_liabilities as $key => $value) {
            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query) {
                $data['current_liabilities'][$key]['account_type_id'] = $value->id;
                $data['current_liabilities'][$key]['account_type_name'] = $value->display_name;
                $data['current_liabilities'][$key]['balance'] = (-1)*$query->balance;
                $data['total_current_liability'] += (-1)*$query->balance;

                $data['current_liabilities'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_current_liability'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }
        }

        $data['total_income_tax_payable'] = $data['total_due_affiliated'] = 0;
        $data['total_liabilities_expenses'] = 0;

        $income_tax_payable = AccountType::where('name', '=', "income_tax_payble")->select('id')->first();
        $income_tax_payables = AccountType::where('parent_id', $income_tax_payable->id)->select('id', 'display_name')->get();

        $data_income_tax_payable = $income_tax_payable->account_head_balance();
        $data_income_tax_payable =  $pre_income_tax_payable = $this->authCompany($data_income_tax_payable, $request);
        $data_income_tax_payable = $data_income_tax_payable->latestAccountBalance($toDate)->first();
        $pre_income_tax_payable = $pre_income_tax_payable->previousAccountBalance($fromDate)->first();

        $data['income_tax_payable_balance'] = $data['total_income_tax_payable'] = $data_income_tax_payable ? (-1)*$data_income_tax_payable->balance : 0;
        $data['pre_income_tax_payable_balance'] =  $data['pre_total_income_tax_payable'] = $pre_income_tax_payable ? (-1)*$pre_income_tax_payable->balance : 0;
        $data['income_tax_payables'] = [];
        foreach ($income_tax_payables as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();

            if ($query)
            {
                $data['income_tax_payables'][$key]['account_type_id'] = $value->id;
                $data['income_tax_payables'][$key]['account_type_name'] = $value->display_name;
                $data['income_tax_payables'][$key]['balance'] = (-1)*$query->balance;
                $data['total_income_tax_payable'] += (-1)*$query->balance;

                $data['income_tax_payables'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_income_tax_payable'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }

        }


        $liabilities_expense = AccountType::where('name', '=', "liabilities_for_expenses")->select('id')->first();

        $data_liabilities_expense = $liabilities_expense->account_head_balance();
        $data_liabilities_expense = $pre_liabilities_expense = $this->authCompany($data_liabilities_expense, $request);
        $data_liabilities_expense = $data_liabilities_expense->latestAccountBalance($toDate)->first();
        $pre_liabilities_expense = $pre_liabilities_expense->previousAccountBalance($toDate)->first();

        $data['liabilities_expenses_balance'] = $data['total_liabilities_expenses'] = $data_liabilities_expense ? (-1)*$data_liabilities_expense->balance : 0;
        $data['pre_liabilities_expenses_balance'] =  $data['pre_total_liabilities_expenses'] = $pre_liabilities_expense ? (-1)*$pre_liabilities_expense->balance : 0;

        $liabilities_expenses = AccountType::where('parent_id', $liabilities_expense->id)->select('id', 'display_name')->get();
        $data['liabilities_expenses'] = $data['due_to_affiliated_companies'] = [];
        foreach ($liabilities_expenses as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query)
            {
                $data['liabilities_expenses'][$key]['account_type_id'] = $value->id;
                $data['liabilities_expenses'][$key]['account_type_name'] = $value->display_name;
                $data['liabilities_expenses'][$key]['balance'] = (-1)*$query->balance;
                $data['total_liabilities_expenses'] += (-1)*$query->balance;

                $data['liabilities_expenses'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_liabilities_expenses'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }

        }

        $due_to_affiliated_company = AccountType::where('name', '=', "due_to_affiliated_company")->select('id')->first();
        $due_to_affiliated_companies = AccountType::where('parent_id', $due_to_affiliated_company->id)->select('id', 'display_name')->get();

        $data_due_to_affiliated_company = $due_to_affiliated_company->account_head_balance();
        $data_due_to_affiliated_company = $pre_due_to_affiliated_company = $this->authCompany($data_due_to_affiliated_company, $request);
        $data_due_to_affiliated_company = $data_due_to_affiliated_company->latestAccountBalance($toDate)->first();
        $pre_due_to_affiliated_company = $pre_due_to_affiliated_company->previousAccountBalance($fromDate)->first();

        $data['due_to_affiliated_company_balance'] = $data['total_due_affiliated'] = $data_due_to_affiliated_company ? (-1)*$data_due_to_affiliated_company->balance : 0;
        $data['pre_due_to_affiliated_company_balance'] = $data['pre_total_due_affiliated'] = $pre_due_to_affiliated_company ? (-1)*$pre_due_to_affiliated_company->balance : 0;

        foreach ($due_to_affiliated_companies as $key => $value) {

            $query = $value->account_head_balance()->latestAccountBalance($toDate);
            $query = $this->authCompany($query, $request);
            $query = $query->first();


            $previous_query = $value->account_head_balance()->previousAccountBalance($fromDate);
            $previous_query = $this->authCompany($previous_query, $request);
            $previous_query = $previous_query->first();


            if ($query)
            {
                $data['due_to_affiliated_companies'][$key]['account_type_id'] = $value->id;
                $data['due_to_affiliated_companies'][$key]['account_type_name'] = $value->display_name;
                $data['due_to_affiliated_companies'][$key]['balance'] = (-1)*$query->balance;
                $data['total_due_affiliated'] += (-1)*$query->balance;

                $data['due_to_affiliated_companies'][$key]['pre_balance'] = $previous_query ? (-1)*$previous_query->balance : 0;
                $data['pre_total_due_affiliated'] += $previous_query ? (-1)*$previous_query->balance : 0;
            }

        }


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
        $pre_fromDate  = Transactions::authCompany()->select('date')->first();
        $pre_fromDate = $data['pre_fromDate'] = $pre_fromDate->date;
        $pre_toDate = Carbon::parse($fromDate)->subDay(1);
        $data['pre_toDate'] = $pre_toDate->toDateString();
        $pre_sales = $pre_sale_details = SaleDetails::whereBetween('date', [$pre_fromDate, $pre_toDate]);

        $pre_sales = $this->authCompany($pre_sales, $request);
        $data['pre_total_sales'] = $pre_sales->sum('total_price');

        $sale_details = $this->authCompany($sale_details, $request);
        $pre_sale_details = $this->authCompany($pre_sale_details, $request);

        $data['sale_details'] = $sale_details->groupBy('item_id')
            ->selectRaw('*, sum(total_price) as sum_total_price')
            ->get();

        $data['pre_sale_details'] = $pre_sale_details->groupBy('item_id')
            ->selectRaw('*, sum(total_price) as sum_total_price')
            ->get();


        $purchases = $purchase_details = PurchaseDetail::whereBetween('date', [$fromDate, $toDate]);
        $purchases = $this->authCompany($purchases, $request);
        $data['total_purchases'] = $purchases->sum('total_price');

        $pre_purchases = $pre_purchase_details = PurchaseDetail::whereBetween('date', [$pre_fromDate, $pre_toDate]);
        $pre_purchases = $this->authCompany($pre_purchases, $request);
        $data['pre_total_purchases'] = $pre_purchases->sum('total_price');

        $purchase_details = $this->authCompany($purchase_details, $request);
        $data['purchase_details'] = $purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(total_price) as sum_total_price')
            ->get();

        $pre_purchase_details = $this->authCompany($pre_purchase_details, $request);
        $data['pre_purchase_details'] = $pre_purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(total_price) as sum_total_price')
            ->get();


        $cost_of_sold = AccountType::where('name', '=', "cost_of_goods_sold")->select('id')->first();
        $cost_of_sold_items = AccountType::where('parent_id', $cost_of_sold->id)->select('id', 'display_name')->get();

        $cost_of_sold_balance = $cost_of_sold->account_head_balance();
        $cost_of_sold_balance = $pre_cost_of_sold_balance = $cost_of_sold_balance->authCompany($cost_of_sold_balance, $request);
        $cost_of_sold_balance = $cost_of_sold_balance->latestAccountBalance($toDate)->first();
        $pre_cost_of_sold_balance = $pre_cost_of_sold_balance->previousAccountBalance($toDate)->first();

        $data['cost_of_sold_balance'] = $cost_of_sold_balance ? $cost_of_sold_balance->balance : 0;
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
                $data['cost_of_sold_items'][$key]['account_type_name'] = $value->display_name;
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

                    $previous_query2 = $value2->account_head_balance()->previousAccountBalance($fromDate);
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

                    $previous_query = $value2->account_head_balance()->previousAccountBalance($fromDate);
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

        $data['net_profit'] = ($data['total_sales'] + $data['total_incomes']) - $data['total_expenses'] - $total_cost_of_sold;

        $data['pre_net_profit'] = ($data['pre_total_sales'] + $data['pre_total_incomes']) - $data['pre_total_expenses'] - $pre_total_cost_of_sold;

//        dd($data);
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year->first() : null;
        $data['fiscal_year'] = $fiscal_year->get()->pluck('title', 'id');

        $data['report_title'] = "Balance Sheet <br/> " . db_date_month_year_format($fromDate) . ($toDate ? " to " . db_date_month_year_format($toDate) : "");
        $data['companies'] = Company::get()->pluck('company_name', 'id');

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
//            return view('member.reports.balance_sheet', $data);
            }
        }
    }
}
