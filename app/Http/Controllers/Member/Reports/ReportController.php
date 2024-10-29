<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SupplierOrCustomer;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends BaseReportController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all_transaction(Request $request)
    {
        $query = $this->search_data($request, $get = false);
        $data = [];
        $data = $this->get_search_data($data);
        $data['title'] = $data['report_title'] = 'All Transaction';
        $data['type'] = $type = 'Transaction';
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $query = $this->transactionTypeCheck($query, $type);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['report_title'] = $title = 'Print All Transaction report ';
            $data['modal'] = $query->get();

            if ($request->type == "print") {
                return view('member.reports.all_print', $data);
            } else {
                $pdf = PDF::loadView('member.reports.all_print', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.all_transactions', $data);
        }

    }

    public function report_list()
    {
        return view('member.reports.report_list');
    }

    public function all_transfer(Request $request)
    {
        $query = $this->search_data($request);

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['inputs'] = [];
        foreach ($request->all() as $key => $value) {
            if ($key == 'from_account_type_id') {
                $acc = AccountType::find($request->from_account_type_id);

                if ($acc)
                    $data['inputs'][$key] = "Account From : " . $acc->display_name;
            } else if ($key == 'to_account_type_id') {
                $acc = AccountType::find($request->to_account_type_id);
                if ($acc)
                    $data['inputs'][$key] = "Account To : " . $acc->display_name;
            } else {

                if ($value)
                    $data['inputs'][$key] = human_words($key) . " : " . $value;
            }
        }


        $data['title'] = $data['report_title'] = $title = 'All Transfer Report';
        $data['type'] = $type = 'Transfer';
        $data = $this->get_search_data($data);
        $query = $this->transactionTypeCheck($query, $type);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['modal'] = $query->get();

            if ($request->type == "print") {
                return view('member.reports.all_print', $data);
            } else {
                $pdf = PDF::loadView('member.reports.all_print', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.all', $data);
        }
    }

    public function all_income(Request $request)
    {
        $query = $this->search_data($request);

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['inputs'] = [];
        foreach ($request->all() as $key => $value) {
            if ($key == 'from_account_type_id') {
                $acc = AccountType::find($request->from_account_type_id);

                if ($acc)
                    $data['inputs'][$key] = "Account From : " . $acc->display_name;
            } else if ($key == 'to_account_type_id') {
                $acc = AccountType::find($request->to_account_type_id);
                if ($acc)
                    $data['inputs'][$key] = "Account To : " . $acc->display_name;
            } else {

                if ($value)
                    $data['inputs'][$key] = human_words($key) . " : " . $value;
            }
        }

        $data['title'] = $data['report_title'] = $title = 'All Income Report';
        $data['type'] = $type = 'Income';

        $data = $this->get_search_data($data);
        $query = $this->transactionTypeCheck($query, $type);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download") {
            $data['modal'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.all_print', $data);
            } else {
                $pdf = PDF::loadView('member.reports.all_print', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.all', $data);
        }
    }

    public function all_report_print($type, Request $request)
    {

        $query = $this->search_data($request, $get = false);

        $query = $this->transactionTypeCheck($query, $type);
        $data['modal'] = $query->get();
        $data['report_title'] = $title = 'Print All ' . $type . ' report ';

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print") {
            return view('member.reports.all_print', $data);
            $data = $this->company($data);
        } else {
            $pdf = PDF::loadView('member.reports.all_print', $data);
            $file_name = file_name_generator($title);
            return $pdf->download($file_name.".pdf");
        }
    }

    public function all_report_list($type, Request $request)
    {
        // dd('dss');

        $data['modal'] = $this->search_data($request, true);

        $data['title'] = $data['report_title'] = 'All ' . $type . ' report ';
        $data['filename'] = filename('Income report') . 'pdf';
        $data['filepage'] = 'member.reports.pdf_print';

        $data = $this->company($data);

        // Generate Consignments Report PDF
//        $all_income_report = new \App\Acme\pdfGenerator\Transactions($data);
        $pdf = PDF::loadHTML('<h1>Test</h1>');
//        $pdf = PDF::loadHTML('member.reports.pdf_print', $data);
//        return $pdf->download($data['filename']);
        return $pdf->stream();

    }

    public function all_income_datatable()
    {
        $transaction = Transactions::with('transaction_details', 'created_user', 'cash_or_bank_account',
            'account_type');

        return DataTables::of($transaction)->make(true);
    }

    public function all_expense(Request $request)
    {
        $query = $this->search_data($request);

        $data['inputs'] = [];
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        foreach ($request->all() as $key => $value) {
            if ($key == 'from_account_type_id') {
                $acc = AccountType::find($request->from_account_type_id);

                if ($acc)
                    $data['inputs'][$key] = "Account From : " . $acc->display_name;
            } else if ($key == 'to_account_type_id') {
                $acc = AccountType::find($request->to_account_type_id);
                if ($acc)
                    $data['inputs'][$key] = "Account To : " . $acc->display_name;
            } else {

                if ($value)
                    $data['inputs'][$key] = human_words($key) . " : " . $value;
            }
        }

        $data['title'] = $data['report_title'] = $title = 'All Expense Report';
        $data['type'] = $type = 'Expense';
        $query = $this->transactionTypeCheck($query, $type);
        $data = $this->get_search_data($data);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download") {
            $data['modal'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.all_print', $data);
            } else {
                $pdf = PDF::loadView('member.reports.all_print', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.all', $data);
        }
    }

    public function all_journal_entry(Request $request)
    {
        $query = $this->search_data($request);

        $data['inputs'] = [];
        foreach ($request->all() as $key => $value) {
            if ($key == 'from_account_type_id') {
                $acc = AccountType::find($request->from_account_type_id);

                if ($acc)
                    $data['inputs'][$key] = "Account From : " . $acc->display_name;
            } else if ($key == 'to_account_type_id') {
                $acc = AccountType::find($request->to_account_type_id);
                if ($acc)
                    $data['inputs'][$key] = "Account To : " . $acc->display_name;
            } else {

                if ($value)
                    $data['inputs'][$key] = human_words($key) . " : " . $value;
            }
        }


        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['title'] = $data['report_title'] = $title = 'All Journal Entry';
        $data['type'] = $type = 'Journal Entry';
        $query = $this->transactionTypeCheck($query, $type);
        $data = $this->get_search_data($data);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['modal'] = $query->get();

            if ($request->type == "print") {
                return view('member.reports.all_print', $data);
            } else {
                $pdf = PDF::loadView('member.reports.all_print', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.all', $data);
        }
    }

    public function cost_report($cost_type, Request $request)
    {

        $inputs = $request->all();

        $query = new Purchase();
        if (isset($request->memo_no)) {
            $query = $query->where('memo_no', $request->memo_no);
        }

        if (isset($request->chalan)) {
            $query = $query->where('chalan', $request->chalan);
        }

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        }
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['branches'] = Branch::get()->pluck('display_name', 'id');

        $data['cost_type'] = $cost_type;

        if ($cost_type == "transport") {
            $query = $query->where("transport_cost", '>', 0);
        } else {
            $query = $query->where("unload_cost", '>', 0);
        }

        $data['report_title'] = ucfirst($cost_type) . ' Cost Report';

        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            $data['modal'] = $query->get();
            if ($request->type == "print") {
                return view('member.reports.print_cost_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_cost_report', $data);
                $file_name = file_name_generator("Cost_Report_");
                return $pdf->download($file_name.".pdf");
            }

        } else {
            $data['modal'] = $query->paginate(20)->appends(request()->query());
            return view('member.reports.cost_report', $data);
        }

    }

    public function account_day_wise_last_balance(Request $request)
    {

        $inputs = $request->all();
        $query = new AccountHeadDayWiseBalance();

        $query = $query->where('account_type_id', $request->account_type_id);

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        else {

            $start = new Carbon('first day of this month');
            $inputs['from_date'] = db_date_format($start);
        }

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->whereDate('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->whereDate('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {

            $query = $query->whereDate('date', '>=', $fromDate);
            $query = $query->whereDate('date', '<=', $toDate);
        }

        if (isset($inputs['account_type_id'])) {
            $data['account_types'] = AccountType::select('id', 'display_name')->where('id', $inputs['account_type_id'])->first();
        }

        $data['accounts'] = AccountType::active()->get()->pluck('display_name', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $data['full_url'] = $request->fullUrl();
        $query = $this->authCompany($query, $request);

        $data = $this->company($data);
        $data['report_title'] = $title = "Account Head Daywise Balance";
        $data['modal'] = $query->orderBy('date', 'asc')->get();
        if($request->type=="print"|| $request->type=="download") {

            if ($request->type == "print") {
                return view('member.reports.print_account_day_wise_last_balance', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_account_day_wise_last_balance', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name.".pdf");
            }

        } else {
            return view('member.reports.account_day_wise_last_balance', $data);
        }
    }

    public function sharer_due_report($type, Request $request)
    {
        $inputs = $request->all();
        $query = SupplierOrCustomer::authCompany();
        $sharers = SupplierOrCustomer::authCompany();

        if ($type == "customer") {
            $data['type'] = $type = "customer";
            $query = $query->onlyCustomers();
            $sharers = $sharers->onlyCustomers();
        } else {
            $data['type'] = $type = "supplier";
            $query = $query->onlySuppliers();
            $sharers = $sharers->onlySuppliers();
        }

        if (!empty($inputs['sharer_id'])) {
            $query = $query->where('id', $inputs['sharer_id']);
        }

        $data['sharers'] = $sharers->get()->pluck('name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        // $data['report_title'] = ucfirst($type) . " ".trans('common.due_report');
        $data['report_title'] = trans('common.'.$type) . " ".trans('common.due_report');
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $query = $this->authCompany($query, $request);

        if ($request->type == "print" || $request->type == "download") {
            $data['modal'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.print_sharer_due_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_sharer_due_report', $data);
                $file_name = file_name_generator(ucfirst($type) . "_Due_Report");
                return $pdf->download($file_name.".pdf");
            }
        } else {
            $data['modal'] = $query->paginate(10)->appends(request()->query());
            return view('member.reports.sharer_due_report', $data);
        }

    }

    public function inventory_due_report($type, Request $request)
    {

        $inputs = $request->all();

        if ($type == "sale") {
            $data['type'] = $type = "Sale";
            $sharer_type = "customer";
            $query = Sale::authMember()->authCompany()->where('due', '>', 0);
            $sharers = SupplierOrCustomer::onlyCustomers();
            if (!empty($inputs['sharer_id'])) {
                $query = $query->where('customer_id', '=', $inputs['sharer_id']);
            }

        } else {
            $data['type'] = $type = "Purchase";
            $sharer_type = "supplier";
            $query = Purchase::authMember()->authCompany()->where('due_amount', '>', 0);
            $sharers = SupplierOrCustomer::onlySuppliers();
            if (!empty($inputs['sharer_id'])) {
                $query = $query->where('supplier_id', '=', $inputs['sharer_id']);
            }
        }

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);


        if (empty($fromDate) && (!empty($toDate))) {

            $query = $query->where('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {

            $query = $query->where('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {
            $query = $query->whereBetween('date',[$fromDate, $toDate]);
        }

            if (isset($inputs['account_type_id'])) {
                $account_types = AccountType::select('id', 'display_name')->where('id', $inputs['account_type_id']);
                $account_types = $this->authCompany($account_types, $request);
                $data['account_types'] = $account_types->get();
            } else {
                $account_types = AccountType::select('id', 'display_name');
                $account_types = $this->authCompany($account_types, $request);
                $data['account_types'] = $account_types->get();

            }
//        $data['sharers'] = SupplierOrCustomer::authMember()->where('customer_type', '=', $sharer_type)->active()->get()->pluck('name','id');
            $data['sharers'] = $sharers->get()->pluck('name', 'id');
            $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
            // $data['report_title'] = $type . " Due Report" . $fromDate . ($toDate ? " - " . $toDate : "");
            $data['report_title'] = trans('common.'.strtolower($type)) . " ".trans('common.due_report') . $fromDate . ($toDate ? " - " . $toDate : "");
            $data['companies'] = Company::get()->pluck('company_name', 'id');


            $data['accounts'] = AccountType::active();
            $data['accounts'] = $this->authCompany($data['accounts'], $request);
            $data['accounts'] = $data['accounts']->get()->pluck('display_name', 'id');

            $query = $this->authCompany($query, $request);

            if ($request->type == "print" || $request->type == "download") {
                $data['modal'] = $query->get();
                $data = $this->company($data);

                if ($request->type == "print") {
                    return view('member.reports.print_inventory_due_report', $data);
                } else if ($request->type == "download") {
                    $pdf = PDF::loadView('member.reports.print_inventory_due_report', $data);
                    $file_name = file_name_generator($type . "_Due_Report");
                    return $pdf->download($file_name . ".pdf");
                }

            } else {

                $data['modal'] = $query->paginate(10)->appends(request()->query());
                return view('member.reports.inventory_due_report', $data);
            }

    }

    public function sharer_due_collection_report($type, Request $request)
    {
        if ($type == "customer") {
            $condition = "Sale due payment";
            $sharers = SupplierOrCustomer::authMember()->onlyCustomers();
        } else {
            $condition = "Purchase due payment";
            $sharers = SupplierOrCustomer::authMember()->onlySuppliers();
        }

        $data['type'] = $type;
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        $inputs = $request->all();
        $query = DB::table('transactions')
            ->leftJoin('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('suppliers_or_customers', 'transactions.supplier_id', '=', 'suppliers_or_customers.id')
            ->select('transactions.*', 'transaction_details.*', 'suppliers_or_customers.name as sharer_name')
            ->where('transaction_details.description', 'like', "%" . $condition . "%")
            ->orderBy('transactions.id', 'ASC')
            ->groupBy('transactions.id');

        if (!empty($inputs['sharer_id'])) {
            $query = $query->where('supplier_id', '=', $inputs['sharer_id']);
        }

        $query = $this->transactionDateCheck($query, $inputs);

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        $data['sharers'] = $sharers->get()->pluck('name', 'id');
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
//        dd($query->get());

        // $data['report_title'] = $title = ucfirst($type) . " Due Collection Report" . $fromDate . ($toDate ? " - " . $toDate : "");
        $data['report_title'] = $title = trans('common.'.$type) . " ".trans('common.due_collection_report') . $fromDate . ($toDate ? " - " . $toDate : "");

        $query = $this->authCompany($query, $request, 'transactions');

        if ($request->type == "print" || $request->type == "download") {
            $data['modal'] = $query->get();
            $data = $this->company($data);

            if ($request->type == "print") {
                return view('member.reports.print_sharer_due_collection_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_sharer_due_collection_report', $data);
                $file_name = file_name_generator(ucfirst($type) . "_Due_Collection_Report_");
                return $pdf->download($file_name.".pdf");
            }

        } else {

            $data['modal'] = $query->paginate(10)->appends(request()->query());
            return view('member.reports.sharer_due_collection_report', $data);
        }

    }



}