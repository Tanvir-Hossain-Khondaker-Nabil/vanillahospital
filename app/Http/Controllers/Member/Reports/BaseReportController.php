<?php

namespace App\Http\Controllers\Member\Reports;

use App\Http\Traits\AccountHeadDayWiseBalanceTrait;
use App\Http\Traits\AccountTypeTrait;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\TransactionDetailsTrait;
use App\Models\AccountType;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseReportController extends Controller
{

    use TransactionDetailsTrait, AccountTypeTrait, CompanyInfoTrait, AccountHeadDayWiseBalanceTrait;

    protected $print_system;
    protected $fromDate;
    protected $toDate;
    protected $pre_fromDate;
    protected $pre_toDate;

    public function __construct()
    {
//        $this->check_print_system();
//        $this->checkCompanySet();
//        $this->countStockPrice();
    }

    public function get_search_data($data)
    {
        $query = $this->select_heads($page = 'get');
        $data['accounts'] = $query->get()->pluck('display_name', 'id');
        $query = $this->select_sub_heads($page = 'get');
        $data['account_sub_heads'] = $query->get()->pluck('display_name', 'id');

        $data['account_groups'] = AccountType::group()->get()->pluck('display_name', 'id');

        return $data;
    }

    public function search(Request $request)
    {
        $query = $this->select_heads($page = 'get');
        $data['accounts'] = $query->get()->pluck('display_name', 'id');

        return view('member.reports.search_data', $data);
    }

    public function check_print_system()
    {
        $settings = Setting::where('key', '=', 'print_page_setup')->first();

        if ($settings) {
            $this->print_system = $settings->value;
        } else {
            $this->print_system = '';
        }

        $this->print_system = $this->print_system == "default" ? '' : $this->print_system;
    }

    public function countStockPrice($data = [], $request = null, $condition = null)
    {

        $from_date = db_date_format($request->stock_from_date);
        $to_date = db_date_format($request->stock_to_date);

        $item_price = Item::leftjoin('purchase_details', 'items.id', 'purchase_details.item_id')
            ->leftjoin('sales_details', 'items.id', 'sales_details.item_id')
            ->select(DB::raw('(SUM(purchase_details.total_price)+SUM(sales_details.total_price))/(SUM(purchase_details.qty)+SUM(sales_details.qty)) as price_qty'), 'items.id');

        if($from_date != null && $to_date != null)
        {
            $item_price = $item_price->whereBetween('purchase_details.date', [$from_date, $to_date])->whereBetween('sales_details.date', [$from_date, $to_date]);
        }

        if ($request != null) {
            $item_price = $this->authCompany($item_price, $request, 'items');
            $item_price = $this->authCompany($item_price, $request, 'purchase_details');
            $item_price = $this->authCompany($item_price, $request, 'sales_details');
        }

        if(isset($fromDate))
            $item_price = $item_price->where('sales.id', $condition);

        if ($condition != null) {
            $item_price = $item_price->where('items.id', $condition);
            $item_price = $item_price->where('purchase_details.item_id', $condition);
            $item_price = $item_price->where('sales_details.item_id', $condition);

           $result = $item_price->groupBy('items.id')->first();

           if($result)
                $data['item_price'] = $result;
           else{
               $result = $this->countPurchaseStockPrice([], $request, $condition);

               if($result)
                   $data['item_price'] = $result;
               else
                   $data['item_price'] = $this->countSaleStockPrice([], $request, $condition);

           }


        } else {
            $data['item_price'] = $item_price->groupBy('items.id')->get();
        }


        return $data;
    }

    public function countSaleStockPrice($data = [], $request = null, $condition = null)
    {

        $from_date = db_date_format($request->stock_from_date);
        $to_date = db_date_format($request->stock_to_date);

        $item_price = Item::leftjoin('sales_details', 'items.id', 'sales_details.item_id')
            ->select(DB::raw('(SUM(sales_details.total_price)/SUM(sales_details.qty)) as price_qty'), 'items.id');

        if($from_date != null && $to_date != null)
        {
            $item_price = $item_price->whereBetween('sales_details.date', [$from_date, $to_date]);
        }

        if ($request != null) {
            $item_price = $this->authCompany($item_price, $request, 'items');
            $item_price = $this->authCompany($item_price, $request, 'sales_details');
        }

        if(isset($fromDate))
            $item_price = $item_price->where('sales.id', $condition);

        if ($condition != null) {
            $item_price = $item_price->where('items.id', $condition);
            $item_price = $item_price->where('sales_details.item_id', $condition);
            $result = $item_price->groupBy('items.id')->first();

        } else {
            $result = $item_price->groupBy('items.id')->get();
        }


        return $result;
    }


    public function countPurchaseStockPrice($data = [], $request = null, $condition = null)
    {

        $from_date = db_date_format($request->stock_from_date);
        $to_date = db_date_format($request->stock_to_date);

        $item_price = Item::leftjoin('purchase_details', 'items.id', 'purchase_details.item_id')
            ->select(DB::raw('SUM(purchase_details.total_price)/SUM(purchase_details.qty) as price_qty'), 'items.id');

        if($from_date != null && $to_date != null)
        {
            $item_price = $item_price->whereBetween('purchase_details.date', [$from_date, $to_date]);
        }


        if ($request != null) {
            $item_price = $this->authCompany($item_price, $request, 'items');
            $item_price = $this->authCompany($item_price, $request, 'purchase_details');
        }

        if(isset($fromDate))
            $item_price = $item_price->where('sales.id', $condition);

        if ($condition != null) {
            $item_price = $item_price->where('items.id', $condition);
            $item_price = $item_price->where('purchase_details.item_id', $condition);

            $result = $item_price->groupBy('items.id')->first();

        } else {
            $result = $item_price->groupBy('items.id')->get();
        }


        return $result;
    }


    public function checkCompanySet()
    {
//        if(Auth::user()->company_id == null)
//        {
//            $status = ['type'=>'danger','message'=>'Company Is not assign for You. Please confirm your company Name'];
//            Redirect::to('member/set-users-company')->with('status', $status)->send();
//        }
    }

    public function authCompany($query, $request, $table = "")
    {
        if (!empty($table)) {
            $table = $table . "." . 'company_id';
        } else {
            $table = 'company_id';
        }

        if (isset($request->company_id)) {
            $company_id = $request->company_id;
            $query = $query->where($table, $company_id);
        }else if (isset(Auth::user()->company_id) && Auth::user()->company_id != null) {
            $company_id = Auth::user()->company_id;
            $query = $query->where($table, $company_id);
        }

        return $query;
    }


    protected function search_data(Request $request, $get = false)
    {
        $inputs = $request->all();

        $multiple_condition = [];

        if (!empty($inputs['transaction_code']))
            $multiple_condition['transaction_code'] = $request->transaction_code;

        $query = $this->transaction_full_details(
            $member = true, $company = false, $page = 20, $tr_payment = false, $updated_user = false,
            $tr_category = false, $select_column = "", $group_tr_code = false, $group_tr_type = false,
            $order = 'DESC', '', '', '', $multiple_condition, $get
        );

        $query = $this->transactionDateCheck($query, $inputs);


        if (!empty($inputs['head_account_type_id'])) {
            $query = $query->where('transaction_details.account_type_id', $request->head_account_type_id);
        }

        if (!empty($inputs['d_head_account_type_id'])) {
            $query = $query->where('transaction_details.account_type_id', $request->d_head_account_type_id);
        }

        if (!empty($inputs['group_account_type_id'])) {
            $query = $query->where('transaction_details.account_type_id', $request->group_account_type_id);
        }

        if (!empty($inputs['sub_head_account_type_id'])) {
            $query = $query->where('transaction_details.account_type_id', $request->sub_head_account_type_id);
        }

        if (!empty($inputs['company_id'])) {
            $query = $query->where('transaction_details.company_id', $request->company_id);
        }

        return $query;
    }

    protected function transactionTypeCheck($query, $type)
    {
        if ($type == 'Income') {
            $query = $query->where('transactions.transaction_method', 'Income');
        } elseif ($type == 'Expense') {
            $query = $query->where('transactions.transaction_method', 'Expense');
        } elseif ($type == 'Journal Entry') {
            $query = $query->where('transactions.transaction_method', 'Journal Entry');
        } elseif ($type == 'Transfer') {
            $query = $query->where('transactions.transaction_method', 'Transfer');
        }

        return $query;
    }

    protected function transactionDetailsDateCheck($query, $inputs)
    {
        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('transaction_details.date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('transaction_details.date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereBetween('transaction_details.date', [$fromDate, $toDate]);
        }

        return $query;
    }

    protected function transactionDateCheck($query, $inputs)
    {
        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('transactions.date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('transactions.date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereBetween('transactions.date', [$fromDate, $toDate]);
        }

        return $query;
    }

    protected function previousTotalTransaction($query, $inputs)
    {
        $fromDate = '';
        if (!empty($inputs['from_date'])) {
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
            $query = $query->where('transactions.date', '<', $fromDate);
        }

        if (empty($inputs['from_date'])) {
            $transaction = Transactions::first();
            $query = $query->where('transactions.date', '<', $transaction ? $transaction->date : '');
        }


        return $query;
    }

    protected function saleDateCheck($query, $inputs)
    {
        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->whereDate('created_at', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->whereDate('created_at', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereDate('created_at', '>=', $fromDate);
            $query = $query->whereDate('created_at', '<=', $toDate);
        }

        return $query;
    }

    protected function purchaseDateCheck($query, $inputs)
    {
        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->whereDate('created_at', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->whereDate('created_at', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereDate('created_at', '>=', $fromDate);
            $query = $query->whereDate('created_at', '<=', $toDate);
        }

        return $query;
    }

    protected function checkAccountTypeBalance()
    {

    }

    public function searchDate(Request $request)
    {

        // $inventories = new StockReport();

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $this->fromDate = db_date_format($request->from_date);
            $this->toDate = db_date_format($request->to_date);

        } else if (!empty($request->fiscal_year)) {

            $fiscal_year = FiscalYear::find($request->fiscal_year);
            $this->fromDate = $fiscal_year->start_date;
            $this->toDate = $fiscal_year->end_date;
            
        } else if (!empty($request->year)) {

            $this->fromDate = $request->year . "-01-01";
            $this->toDate = $request->year . "-12-31";
            // $in_date = $inventories->select('date')->orderby('date', 'desc')->whereYear('date', $request->year)->first();
            // $last_date = Transactions::select('date')->orderby('date', 'desc')->whereYear('date', $request->year);
            // $last_date = $last_date->first();
            // $date = $in_date ? $in_date->date : null;
            // $this->toDate = $last_date ? $last_date->date : null;

        } else {

            if (Auth::user()->company) {
                $set_company_fiscal_year = Auth::user()->company->fiscal_year;
                $this->fromDate = $set_company_fiscal_year->start_date;
                $this->toDate = $set_company_fiscal_year->end_date;

            } else {

                $this->fromDate = date('Y'). "-01-01";
                $this->toDate = date('Y') . "-12-31";

                // $from_date = Transactions::authCompany()->select('date')->orderby('date', 'asc');
                // $from_date = $this->authCompany($from_date, $request);
                // $from_date = $from_date->first();
                // $this->fromDate = $from_date ? $from_date->date : null;

                // $last_date = Transactions::authCompany()->select('date')->orderby('date', 'desc');
                // $last_date = $this->authCompany($last_date, $request);
                // $last_date = $last_date->first();
                // $this->toDate = $last_date ? $last_date->date : null;
            }

        }

        $pre_fromDate = Carbon::parse($this->fromDate)->subYear(1);
        $pre_toDate = Carbon::parse($this->toDate)->subYear(1);
        $this->pre_fromDate  = $pre_fromDate->toDateString();
        $this->pre_toDate  = $pre_toDate->toDateString();


    }
}
