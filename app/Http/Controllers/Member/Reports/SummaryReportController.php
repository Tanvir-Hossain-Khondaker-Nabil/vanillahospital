<?php

namespace App\Http\Controllers\Member\Reports;

use App\ExcelCollection\LedgerBookExport;
use App\ExcelCollection\SharerBalanceByManagerExcel;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\CashOrBankAccount;
use App\Models\Company;
use App\Models\District;
use App\Models\Division;
use App\Models\GLAccountClass;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ReturnPurchase;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\SupplierOrCustomer;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\Union;
use App\Models\Upazilla;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class SummaryReportController extends BaseReportController
{

    public function __construct()
    {
//        parent::__construct();
    }

    public function trail_balance(Request $request)
    {

        $inputs = $request->all();

        $data['modal'] = [];
        $data['modal_cash'] = [];
        $data['accounts'] = AccountType::withTrashed()->active()->authCompany()->get()->pluck('display_name', 'id');
        $data['account_types'] = [];
        $data['cash_accounts'] = [];
        $data['opening_balance'] = '';
        $data['cash_banks'] = [];
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['cash_balance'] = 0;
        $data['cash_date'] = '';

//        if (isset($inputs['search'])) {
            $data['search'] = true;
            $accountCash = AccountType::where('display_name', 'like', "%cash%");
            $accountCash = $this->authCompany($accountCash, $request);
            $accountCash = $accountCash->pluck('id')->toArray();

            $childAccountCash = AccountType::where('id', 4)->orWhereIn('parent_id', $accountCash);
            $childAccountCash = $this->authCompany($childAccountCash, $request);
            $childAccountCash = $childAccountCash->pluck('id')->toArray();

            $fromDate = $toDate = '';

            if (!empty($inputs['from_date']))
                $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
            else
                $inputs['from_date'] = $fromDate = Carbon::today();

            if (!empty($inputs['to_date'])) {
                $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
            } else {
                $inputs['to_date'] = Carbon::today();
            }

            $firstDate = Transactions::select('date')->orderBy('date');
            $firstDate = $this->authCompany($firstDate, $request);
            $firstDate = $firstDate->first();

            if (!empty($toDate) && empty($fromDate)) {
                $inputs['from_date'] = $date = $fromDate ? $fromDate : $firstDate->date;
            } elseif (!empty($fromDate)) {
                $inputs['from_date'] = $fromDate;
                $date = \Carbon\Carbon::parse($fromDate)->subDay()->toDateString();
            } else {
                $inputs['from_date'] = $date = $fromDate ? $fromDate : $firstDate->date;
            }

            $data['from_date'] = db_date_month_year_format($inputs['from_date']);
            $data['to_date'] = db_date_month_year_format($inputs['to_date']);

            $data['cash_date'] = db_date_format($date);
            foreach ($childAccountCash as $key => $value) {
                $previousAccountBalance = AccountHeadDayWiseBalance::where('account_type_id', $value);
                $previousAccountBalance = $this->authCompany($previousAccountBalance, $request);
                $previousAccountBalance = $previousAccountBalance->where('date', '<=', $date)->orderby('date', 'desc')->first();
//            $accountBalance = $accountBalance->where('date','<=', $date)->orderby('date', 'desc')->first();


                if ($previousAccountBalance) {
                    $data['cash_banks'][$key]['account_type_id'] = $value;
                    $data['cash_banks'][$key]['account_type_name'] = $previousAccountBalance->account_types->display_name;
                    $data['cash_banks'][$key]['balance'] = $previousAccountBalance->balance;
                    $data['cash_balance'] += $previousAccountBalance->balance;
                }
            }

            $cashQuery = DB::table('transactions')
                ->leftJoin('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->leftJoin('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
                ->select(DB::raw('SUM(transaction_details.amount) as total_amount'), 'transactions.id as t_id',
                    'account_types.id', 'account_types.display_name', 'transaction_details.account_type_id',
                    'transaction_details.transaction_type')
                ->whereIntegerInRaw('transaction_details.account_type_id', $childAccountCash)
                ->orderBy('account_types.display_name')
                ->groupBy('transaction_details.account_type_id', 'transaction_details.transaction_type');


            $query = DB::table('transactions')
                ->leftJoin('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->leftJoin('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
                ->select(DB::raw('SUM(transaction_details.amount) as total_amount'), 'transactions.id as t_id',
                    'account_types.id', 'account_types.display_name', 'transaction_details.account_type_id',
                    'transaction_details.transaction_type')
                ->whereIntegerNotInRaw('transaction_details.account_type_id', $childAccountCash)
                ->orderBy('account_types.display_name')
                ->groupBy('transaction_details.account_type_id', 'transaction_details.transaction_type');


            if (!isset($inputs['opening_balance'])) {
                $data['opening_balance'] = '';
                $query = $query->where('transaction_method', '!=', 'Initial');
                $cashQuery = $cashQuery->where('transaction_method', '!=', 'Initial');
            } else {
                $data['opening_balance'] = $inputs['opening_balance'];
            }


            $cashQuery = $this->transactionDateCheck($cashQuery, $inputs);
            $query = $this->transactionDateCheck($query, $inputs);

            $cashQuery = $this->authCompany($cashQuery, $inputs, 'transactions');
            $query = $this->authCompany($query, $inputs, 'transactions');

            if (!empty($inputs['account_type_id'])) {
                $query = $query->where('transaction_details.account_type_id', $request->account_type_id);
                $cashQuery = $cashQuery->where('transaction_details.account_type_id', $request->account_type_id);
            }


            $account_types = $query->distinct('account_type_id')->pluck('account_type_id')->toArray();
            $cash_account_types = $cashQuery->distinct('account_type_id')->pluck('account_type_id')->toArray();
//        dd($account_types);
//        dd($query->toSQl());
//        dd($query->get())

            $query = $query->having('total_amount', '!=', 0);
            $cashQuery = $cashQuery->having('total_amount', '!=', 0);

            if (isset($inputs['account_type_id'])) {
                $data_account_types = AccountType::where('id', $inputs['account_type_id']);
            } else {
                $data_account_types = AccountType::whereIntegerInRaw('id', $account_types);
            }

            $data['account_types'] = $data_account_types->withTrashed()->authCompany()->select('id', 'display_name')->orderBy('display_name')->get();

            $data['accounts'] = AccountType::withTrashed()->active()->whereIntegerInRaw('id', $account_types)->get()->pluck('display_name', 'id');

            $data['cash_accounts'] = AccountType::withTrashed()->active()->whereIntegerInRaw('id', $cash_account_types)->select('display_name', 'id')->get();
            $data['modal'] = $query->get();
            $data['modal_cash'] = $cashQuery->get();
//        }

        $data['full_url'] = $request->fullUrl();
        $data = $this->company($data);
        $data['report_title'] = $title = "Trail Balance <br/>". ($data['from_date']) . (!empty($data['to_date']) ? " to " . ($data['to_date']) : "");

        if ($request->type == "print" || $request->type == "download") {

            if ($request->type == "print") {
                return view('member.reports.print_trail_balance', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_trail_balance', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.trail_balance', $data);
        }
    }

    public function trail_balance_v2(Request $request)
    {

        $inputs = $request->all();

        $data['modal'] = [];
        $data['modal_cash'] = [];
        $data['account_types'] = [];
        $data['cash_accounts'] = [];
        $data['opening_balance'] = '';
        $data['cash_banks'] = [];
        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['cash_balance'] = 0;
        $data['cash_date'] = '';
        $data['search'] = '';

        if (isset($inputs['search'])) {
            $data['search'] = $inputs['search'];

            if (
                isset(Auth::user()->company_id)
                && Auth::user()->company_id != null
            ) {
                $request['company_id'] = Auth::user()->company_id;
            }

            $this->searchDate($request);
            $request['fromDate'] = $this->fromDate;
            $request['toDate'] = $this->toDate;
            $request['pre_toDate'] = $this->pre_toDate;
            $request['pre_fromDate'] = $this->pre_fromDate;
            $request['trail_balance'] = $data['trail_balance'] = true;
            $request['returnData'] = true;

            if (!$this->fromDate || !$this->toDate) {
                $status = ['type' => 'danger', 'message' => 'There is no data in that date/year for Balance Sheet'];
                return redirect()->back()->with('status', $status);
            }

            $data['fromDate'] = $date = $this->fromDate;
            $data['toDate'] = $this->toDate;
            $data['pre_toDate'] = $this->pre_toDate;
            $data['pre_fromDate'] = $this->pre_fromDate;
            $data['trail_balance'] = true;
            Session::put('account_id', '');

            $accountBalanceReport = new AccountBalanceReportControllerV2();
            $data = $accountBalanceReport->cash_bank_report($request, $data);
            $data = $accountBalanceReport->fixed_asset_report($request, $data);

            $data = $accountBalanceReport->current_asset_report($request, $data);
            $data = $accountBalanceReport->advance_prepayment_report($request, $data);
            $data = $accountBalanceReport->fixed_deposits_receipts_report($request, $data);
            $data = $accountBalanceReport->due_companies_report($request, $data);


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

            $data = $accountBalanceReport->expense_report($request, $data);
            $data = $accountBalanceReport->income_report($request, $data);
            $data = $accountBalanceReport->sales_report($request, $data);
            $data = $accountBalanceReport->purchases_report($request, $data);
//            $data = $accountBalanceReport->cost_of_sold_report($request, $data);
            $data = $accountBalanceReport->undefined_parent_report($request, $data);


            $data['total_dr'] = $accountBalanceReport->total_dr;
            $data['total_cr'] = $accountBalanceReport->total_cr;

        }

//        dd($data);
        $data['full_url'] = $request->fullUrl();
        $data = $this->company($data);
        $data['report_title'] = $title = "Trail Balance <br/> " . db_date_month_year_format($this->fromDate) . ($this->toDate ? " to " . db_date_month_year_format($this->toDate) : "");

        $fiscal_year = FiscalYear::authCompany();
        $data['set_company_fiscal_year'] = Auth::user()->company ? Auth::user()->company->fiscal_year->first() : null;
        $data['fiscal_year'] = $fiscal_year->get()->pluck('title', 'id');
        $data['companies'] = Company::get()->pluck('company_name', 'id');

        if ($request->type == "print" || $request->type == "download" || $request->type == "print_details") {

            if ($request->type == "print") {
                return view('member.reports.print_trail_balance_v2', $data);
            } else if ($request->type == "print_details") {
                return view('member.reports.print_trail_balance_details', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_trail_balance_v2', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.trail_balance_v2', $data);
        }
    }

    public function ledger_book(Request $request)
    {

        $inputs = $request->all();

        if (empty($inputs['from_date'])) {
            $start = new Carbon('first day of this month');
            $inputs['from_date'] = db_date_format($start);
        }

        if (!empty($inputs['account_type_id'])) {
            $query = DB::table('transactions')
                ->leftJoin('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->leftJoin('suppliers_or_customers', 'transactions.supplier_id', '=', 'suppliers_or_customers.id')
                ->leftJoin('cash_or_bank_accounts', 'transactions.cash_or_bank_id', '=', 'cash_or_bank_accounts.id')
                ->leftJoin('payment_methods', 'transaction_details.payment_method_id', '=', 'payment_methods.id')
                ->leftJoin('account_types as a', 'transaction_details.account_type_id', '=', 'a.id')
                ->leftJoin('account_types as b', 'transaction_details.against_account_type_id', '=', 'b.id')
                ->leftJoin('account_types as tr_head', 'cash_or_bank_accounts.account_type_id', '=', 'tr_head.id')
                ->select('transactions.*',
                    'tr_head.display_name as tr_account_name',
                    'transaction_details.*',
                    'transaction_details.amount as td_amount',
                    'transaction_details.description as remarks',
                    'suppliers_or_customers.name as sharer_name',
                    'a.display_name as account_name',
                    'b.display_name as against_account_type_name',
                    'cash_or_bank_accounts.title',
                    'payment_methods.name as payment_type_name'
                )
                ->orderBy('transaction_details.date')
                ->where('transaction_details.amount', '>', 0);


            $query = $this->transactionDetailsDateCheck($query, $inputs);


            if (!empty($inputs['from_date'])) {
                $date = db_date_format($inputs['from_date']);
                $accountBalance = AccountHeadDayWiseBalance::where('account_type_id', $request->account_type_id)
                    ->where('date', '<', $date);

                $accountBalance = $this->authCompany($accountBalance, $request);
                $accountBalance = $accountBalance->orderby('date', 'desc')->first();

                $data['bf_balance'] = $accountBalance ? $accountBalance->balance : 0;
                $data['bf_date'] = $date;
            }

            $query = $query->where('transaction_details.account_type_id', $request->account_type_id);
            $query = $this->authCompany($query, $request, 'transaction_details');

            $data['modal'] = $query->get();

            $data['account_types'] = AccountType::select('id', 'display_name', 'deleted_at')->where('id', $inputs['account_type_id'])->withTrashed()->authCompany()->first();
        } else {
            $data['modal'] = [];
        }

        $data['full_url'] = $request->fullUrl();
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['report_title'] = $title = "Ledger Book";

        $data['accounts'] = AccountType::withTrashed()->active()->authCompany()->get()->pluck('display_name', 'id');

        if ($request->type == "print" || $request->type == "download" || $request->type == "excel") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.print_ledger_book', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_ledger_book', $data);
                $file_name = file_name_generator("Ledger_Book_");

                return $pdf->download($file_name . ".pdf");
            } else if ($request->type == "excel") {
                $file_name = file_name_generator("Ledger_Book_");
                $excel = new LedgerBookExport();
                return $excel->make($file_name, $data);
            }
        } else {
            return view('member.reports.ledger_book', $data);
        }

    }

    public function daily_sheet(Request $request)
    {
        $inputs = $request->all();

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

//        if (!empty($inputs['to_date']))
//            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        $data['lastTotalDebit'] = $data['lastTotalCredit'] = 0;

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('cash_or_bank_id');
        $supplier_or_customers = $this->authCompany($supplier_or_customers, $request);
        $supplier_or_customers = $supplier_or_customers->get()->pluck('cash_or_bank_id');

        $cash_banks = CashOrBankAccount::whereIntegerNotInRaw('id', $supplier_or_customers);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->get()->pluck('account_type_id');


        $query = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->leftJoin('items', 'items.id', '=', 'purchase_details.item_id')
            ->select('transaction_details.id as transaction_details_id',
                'transactions.id as transactions_id',
                'transactions.transaction_method',
                'transaction_details.date',
                'transaction_details.transaction_type',
                'transaction_details.payment_method_id',
                'transaction_details.account_type_id',
                'items.item_name',
                'items.unit as item_unit',
                'transactions.sale_id',
                'transactions.purchase_id',
                'transaction_details.against_account_name',
                'transaction_details.description',
                'purchase_details.item_id as item_id',
                'purchase_details.qty as purchase_qty',
                'purchase_details.price as purchase_price',
                'purchase_details.total_price as purchase_total_price',
                'transaction_details.amount as td_amount',
                'account_types.display_name as account_name',
                'transaction_details.account_type_id',
                'transactions.company_id'
            )
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id');


        $debitAmountByHead = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->select('account_types.display_name as account_name',
                'transaction_details.account_type_id',
                'transaction_details.payment_method_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transaction_details.account_type_id', '!=', 35)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id')
            ->groupBy('transaction_details.account_type_id');

        $debitAmountByHeadByItem = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->select('account_types.display_name as account_name',
                'transaction_details.account_type_id',
                'transaction_details.payment_method_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transactions.purchase_id', '>', 0)
            ->where('transaction_details.account_type_id', '==', 35)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id')
            ->groupBy('transaction_details.account_type_id')
            ->groupBy('purchase_details.item_id');

        $sales = SaleDetails::select('item_id', DB::raw('SUM(total_price) as sale_total_price'), DB::raw('COUNT(*) as count'), DB::raw('SUM(qty) as sale_qty'))->where('sale_status', 'sale')->groupBy('item_id');

        $purchases = PurchaseDetail::select('item_id', DB::raw('SUM(total_price) as purchase_total_price'), DB::raw('COUNT(*) as count'), DB::raw('SUM(qty) as purchase_qty'))->groupBy('item_id');

        $bank_payment = PaymentMethod::where('name', 'Bank')->first();
        $cash_payment = PaymentMethod::where('name', 'Cash')->first();

        if (empty($fromDate) && empty($toDate)) {

            $query = $query->where('transactions.date', '=', Carbon::today());
            $debitAmountByHead = $debitAmountByHead->where('transactions.date', '=', Carbon::today());
            $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.date', '=', Carbon::today());

        } elseif (!empty($fromDate) && empty($toDate)) {

            $query = $query->where('transactions.date', '=', $fromDate);
            $debitAmountByHead = $debitAmountByHead->where('transactions.date', '=', $fromDate);
            $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.date', '=', $fromDate);

        }


        $accountCash = AccountType::where('display_name', 'like', "%cash%")->authCompany()->pluck('id');
        $accountBank = AccountType::where('name', 'like', "bank")->authCompany()->pluck('id');

        $childAccountCash = AccountType::where('id', 4)->orWhereIn('parent_id', $accountCash);
        $childAccountCash = $this->authCompany($childAccountCash, $request);
        $childAccountCash = $childAccountCash->get()->pluck('id');

        $childAccountBank = AccountType::onlyBankAccount();
        $childAccountBank = $this->authCompany($childAccountBank, $request);
        $childAccountBank = $childAccountBank->get()->pluck('id');

        $banks = CashOrBankAccount::whereIntegerInRaw('account_type_id', $childAccountBank)->get()->pluck('account_type_id');

        if (!empty($toDate) && empty($fromDate)) {
            $firstDate = Transactions::select('date')->orderBy('date')->first();
            $date = $fromDate ? $fromDate : $firstDate->date;

        } elseif (!empty($fromDate)) {
            $date = $fromDate;
        } else {

            $date = Carbon::today();
        }

        $data['cash_banks'] = [];
        $data['cash_balance'] = 0;

        foreach ($accountCash as $key => $value) {
            $accountBalance = AccountHeadDayWiseBalance::where('account_type_id', $value)
                ->where('date', '<', $date)
                ->where('balance', '!=', 0)
                ->orderby('date', 'desc');

            $accountBalance = $this->authCompany($accountBalance, $request);
            $accountBalance = $accountBalance->first();

            if ($accountBalance) {
                $data['cash_banks'][$key]['account_type_id'] = $value;
                $data['cash_banks'][$key]['account_type_name'] = $accountBalance->account_types->display_name;
                $data['cash_banks'][$key]['balance'] = $accountBalance->balance;
                $data['cash_balance'] += $accountBalance->balance;
            }
        }


        $data['transaction_method'] = isset($inputs['transaction_method']) ? $inputs['transaction_method'] : "";

        if (!empty($inputs['transaction_method'])) {
            if ($inputs['transaction_method'] == "due") {

                $query = $query->where('transaction_details.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHead = $debitAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);

            } elseif ($inputs['transaction_method'] == "cash") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $childAccountCash)->where('amount', '>', 0)->where('date', $date)->get()->pluck('transaction_id');

                $query = $query->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])
                    ->where('transaction_details.payment_method_id', $cash_payment->id);
                $debitAmountByHead = $debitAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])->where('transaction_details.payment_method_id', $cash_payment->id);
                $debitAmountByHeadByItem = $debitAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])->where('transaction_details.payment_method_id', $cash_payment->id);

            } elseif ($inputs['transaction_method'] == "bank") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $banks)->orderBy('transaction_id', 'desc')->get()->pluck('transaction_id');
                $query = $query->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

                $debitAmountByHead = $debitAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);
                $debitAmountByHeadByItem = $debitAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

            } else {
                $query = $query->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHead = $debitAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);
            }

        }


        $query2 = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->leftJoin('items', 'items.id', '=', 'sales_details.item_id')
            ->select('transactions.transaction_method',
                'transaction_details.date',
                'transaction_details.transaction_type',
                'transaction_details.account_type_id',
                'items.item_name',
                'items.unit as item_unit',
                'transaction_details.id as transaction_details_id',
                'transactions.id as transactions_id',
                'transactions.sale_id',
                'transactions.purchase_id',
                'transaction_details.against_account_name',
                'transaction_details.description',
                'sales_details.item_id as item_id',
                'sales_details.qty as sale_qty',
                'sales_details.id as sales_details_id',
                'sales_details.price as sale_price',
                'sales_details.total_price as sale_total_price',
                'transaction_details.amount as td_amount',
                'transaction_details.account_type_id',
                'account_types.display_name as account_name'
            )
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id');


        $creditAmountByHead = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->select(
                'account_types.display_name as account_name',
                'transaction_details.account_type_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transaction_details.account_type_id', '!=', 18)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id')
            ->groupBy('transaction_details.account_type_id');


        $creditAmountByHeadByItem = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->select('account_types.display_name as account_name',
                'transaction_details.account_type_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transactions.sale_id', '>', 0)
            ->where('transaction_details.account_type_id', '==', 18)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id')
            ->groupBy('transaction_details.account_type_id')
            ->groupBy('sales_details.item_id');


        if (!empty($inputs['transaction_method'])) {
            if ($inputs['transaction_method'] == "due") {

                $query2 = $query2->where('transaction_details.transaction_method', '=', $inputs['transaction_method']);
                $creditAmountByHead = $creditAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);

            } elseif ($inputs['transaction_method'] == "cash") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $childAccountCash)->where('amount', '>', "0")->where('date', $date)->get()->pluck('transaction_id');

                $query2 = $query2->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);
                $creditAmountByHead = $creditAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);
                $creditAmountByHeadByItem = $creditAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);


            } elseif ($inputs['transaction_method'] == "bank") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $banks)->where('amount', '>', "0")->get()->pluck('transaction_id');
                $query2 = $query2->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);
                $creditAmountByHead = $creditAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);
                $creditAmountByHeadByItem = $creditAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

            } else {
                $query2 = $query2->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $creditAmountByHead = $creditAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);
            }

        }

        if (empty($fromDate) && empty($toDate)) {

            $query2 = $query2->where('transactions.date', '=', Carbon::today());
            $creditAmountByHead = $creditAmountByHead->where('transactions.date', '=', Carbon::today());
            $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.date', '=', Carbon::today());
            $fromDate = Carbon::today();
            $sales = $sales->whereHas('sale', function ($query) use ($fromDate) {
                $query->where('date', '=', $fromDate);
            });

            $purchases = $purchases->whereHas('purchases', function ($query) use ($fromDate) {
                $query->where('date', '=', $fromDate);
            });
        } elseif (!empty($fromDate)) {
            $query2 = $query2->where('transactions.date', '=', $fromDate);
            $creditAmountByHead = $creditAmountByHead->where('transactions.date', '=', $fromDate);
            $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.date', '=', $fromDate);

            $sales = $sales->whereHas('sale', function ($query) use ($fromDate) {
                $query->where('date', '=', $fromDate);
            });

            $purchases = $purchases->whereHas('purchases', function ($query) use ($fromDate) {
                $query->where('date', '=', $fromDate);
            });
        }

        $daily_sheet_title = "";
        $transaction_method = !empty($inputs['transaction_method']) ? strtolower($inputs['transaction_method']) : "";

        if ($transaction_method == "cash") {
            $daily_sheet_title = "Cash Book";
        } elseif ($transaction_method == "bank") {
            $daily_sheet_title = "Bank Book";
        } elseif ($transaction_method == "sale") {
            $daily_sheet_title = "Sale Book";
        } elseif ($transaction_method == "purchase") {
            $daily_sheet_title = "Purchase Book";
        }

        $data['daily_sheet_title'] = $daily_sheet_title;
        $data['title'] = $data['report_title'] = $transaction_method == "bank" ? 'Daily Cheque Sheet' : 'Daily Sheet';
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['report_date'] = $fromDate ? db_date_month_year_format($fromDate) : "";


        $table = 'transactions';
        $query = $this->authCompany($query, $request, $table);
        $sales = $this->authCompany($sales, $request);
        $purchases = $this->authCompany($purchases, $request);
        $debitAmountByHead = $this->authCompany($debitAmountByHead, $request, $table);
        $debitAmountByHeadByItem = $this->authCompany($debitAmountByHeadByItem, $request, $table);
        $query2 = $this->authCompany($query2, $request, $table);
        $creditAmountByHead = $this->authCompany($creditAmountByHead, $request, $table);
        $creditAmountByHeadByItem = $this->authCompany($creditAmountByHeadByItem, $request, $table);

        $data['transaction_method'] = !empty($inputs['transaction_method']) ? strtolower($inputs['transaction_method']) : "";

        $data['debits'] = $query->get();
        $data['sales'] = $sales->get();
        $data['purchases'] = $purchases->get();
        $data['debitAmountByHead'] = $debitAmountByHead->get();
        $data['debitAmountByHeadByItem'] = $debitAmountByHeadByItem->get();
        $data['credits'] = $query2->get();
        $data['creditAmountByHead'] = $creditAmountByHead->get();
        $data['creditAmountByHeadByItem'] = $creditAmountByHeadByItem->get();

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        if ($request->type == "print" || $request->type == "download" || $request->type == "single_print") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.print_daily_sheet', $data);
            } else if ($request->type == "single_print") {
                return view('member.reports.dual_print_daily_sheet', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_daily_sheet', $data);
                $file_name = file_name_generator("Daily_sheet_Report_");

                return $pdf->download($file_name . ".pdf");
            } else if ($request->type == "excel") {

                $file_name = file_name_generator("Daily_sheet_Report_");

                $excel = new \DailySheetExport();
                $fileExcel = $excel->make($file_name, $data);
                return Excel::download($fileExcel, $file_name . ".xlsx");
            }
        } else {
            return view('member.reports.print_daily_sheet', $data);
        }

    }

    public function ledger_balance_report(Request $request)
    {
        $inputs = $request->all();


        if (empty($inputs['from_date'])) {
            $start = new Carbon('first day of this month');
            $inputs['from_date'] = $fromDate = db_date_format($start);
        } else {
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        }

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = Carbon::today()->toDateString();

        if (!empty($inputs['opening_balance'])) {
            $data['opening_balance'] = $inputs['opening_balance'];
        } else {
            $data['opening_balance'] = '';
        }

        if (!empty($inputs['total_dr_Cr'])) {
            $data['total_dr_Cr'] = $inputs['total_dr_Cr'];
        } else {
            $data['total_dr_Cr'] = "";
        }
//dd($inputs);
        $data['total_closing_balance'] = 0;

        if (
            isset(Auth::user()->company_id)
            && Auth::user()->company_id != null
        ) {
            $company_id = Auth::user()->company_id;
        }

        $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->authCompany()->pluck('account_type_id')->toArray();
        $transactionAccounts = TransactionDetail::whereIntegerNotInRaw('account_type_id', $sharer)->where('date', '<=', $toDate)->distinct('account_type_id')->pluck('account_type_id')->toArray();
        $ledgerBalance = AccountType::whereIntegerInRaw('id', $transactionAccounts)->authCompany()->active()->orderBy('display_name', 'asc')->get();

        $data['modal'] = [];
        foreach ($ledgerBalance as $key => $value) {

            $opening_balance = $value->account_head_balance()->previousAccountBalance($fromDate)->first();

            $bf_balance = $opening_balance ? $opening_balance->balance : 0;

            $total_dr = TransactionDetail::whereHas('transaction', function ($query){
                $query->where('transaction_method', '!=', 'Initial');
            })
                ->where('date', '>=', $fromDate)
                ->where('date', '<=', $toDate)
                ->where('transaction_type', 'dr')
                ->where('account_type_id', $value->id);

            $total_cr = TransactionDetail::whereHas('transaction', function ($query){
                $query->where('transaction_method', '!=', 'Initial');
            })
                ->where('date', '>=', $fromDate)
                ->where('date', '<=', $toDate)
                ->where('transaction_type', 'cr')
                ->where('account_type_id', $value->id);

            if (!Auth::user()->can(['super-admin'])) {
                $total_cr = $total_cr->where('company_id', $company_id);
                $total_dr = $total_dr->where('company_id', $company_id);
            }

            $total_dr = $total_dr->sum('amount');
            $total_cr = $total_cr->sum('amount');

            $closing_balance = $bf_balance + $total_dr - $total_cr;


            if ($bf_balance != 0 || $total_dr > 0 || $total_cr > 0 || $closing_balance != 0) {
                $ledgerBalance[$key]['opening_balance'] = $bf_balance;
                $ledgerBalance[$key]['total_dr'] = $total_dr;
                $ledgerBalance[$key]['total_cr'] = $total_cr;
                $ledgerBalance[$key]['closing_balance'] = $closing_balance != 0 ? $closing_balance > 0 ? create_money_format($closing_balance) . " Dr" : create_money_format($closing_balance * (-1)) . " Cr" : "";

                $last_payment_date = $value->transaction_details()
                    ->orderBy('date', 'desc')
                    ->select('date')
                    ->first();


                $ledgerBalance[$key]['last_payment_date'] = $last_payment_date ? $last_payment_date->date : "";

            }
        }

        $data['modal'] = collect($ledgerBalance)->sortBy('display_name');

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['report_title'] = $title = "GL Balance Report ";


        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.print_ledger_balance', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_ledger_balance', $data);
                $file_name = file_name_generator($title);

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.ledger_balance', $data);
        }
    }

    public function sharer_balance_report(Request $request)
    {
        $inputs = $request->all();
        $customer_type = strtolower($request->customer_type);


        if (empty($inputs['from_date'])) {
            $start = new Carbon('first day of this month');
            $inputs['from_date'] = $fromDate = db_date_format($start);
        } else {
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        }

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = db_date_format(Carbon::today());


        $data['is_blacklist'] = $data['skip_blacklist'] = $data['opening_balance'] = $data['total_dr_Cr'] = $is_blacklist = $skip_blacklist = '';

        if (!empty($inputs['opening_balance'])) {
            $data['opening_balance'] = $inputs['opening_balance'];
        }

        if (!empty($inputs['is_blacklist'])) {
            $data['is_blacklist'] = $is_blacklist = $inputs['is_blacklist'];
        }

        if (!empty($inputs['skip_blacklist'])) {
            $data['skip_blacklist'] = $skip_blacklist = $inputs['skip_blacklist'];
        }


        if (!empty($inputs['total_dr_Cr'])) {
            $data['total_dr_Cr'] = $inputs['total_dr_Cr'];
        }

        $data['total_closing_balance'] = 0;
        $data['modal'] = [];
        $data['customer_type'] = $customer_type;

        if (!empty($inputs['manager_id'])) {

            $manager_id = $inputs['manager_id'];
            $division_id = $inputs['division_id'];
            $district_id = $inputs['district_id'];
            $upazilla_id = $inputs['upazilla_id'];
            $union_id = $inputs['union_id'];
            $area_id = $inputs['area_id'];

            $user = $data['manager_info'] = User::findOrFail($manager_id);
            $sharer = SupplierOrCustomer::where('manager_id', $manager_id)->whereNotNull('account_type_id')->active();
            if (isset($division_id)) {
                $sharer = $sharer->where('division_id', $division_id);
                $data['division'] = Division::find($division_id);
            }

            if (isset($district_id)) {
                $sharer = $sharer->where('district_id', $district_id);
                $data['district'] = District::find($district_id);
            }

            if (isset($upazilla_id)) {
                $sharer = $sharer->where('upazilla_id', $upazilla_id);
                $data['upazilla'] = Upazilla::find($upazilla_id);
            }

            if (isset($union_id)) {
                $sharer = $sharer->where('union_id', $union_id);
                $data['union'] = Union::find($union_id);
            }

            if (isset($area_id)) {
                $sharer = $sharer->where('area_id', $area_id);
                $data['area'] = Area::find($area_id);
            }

            if (!empty($is_blacklist)) {
                $sharer = $sharer->where('is_blacklist', 1);
            }

            if (!empty($skip_blacklist)) {
                $sharer = $sharer->where('is_blacklist', '!=', 1);
            }

        }else{
            $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();
        }



            if ($customer_type == "customer") {
                $sharer = $sharer->where('customer_type', 'customer');
            } else {
                $sharer = $sharer->onlySuppliers();
            }


            $sharer = $sharer->orderBy('name')->with(['totalDr', 'totalCr'])->get();

            foreach ($sharer as $key => $value) {


                if ($value->account_type_id != null) {
                    $opening_balance = $value->account_head_balance()->previousAccountBalance($fromDate)->first();


                    $sharer[$key]['opening_balance'] = $bf_balance = $opening_balance ? $opening_balance->balance : 0;

                    $total_dr = $value->transaction_details()
                        ->where('date', '>=', $fromDate)
                        ->where('date', '<=', $toDate)
                        ->where('transaction_type', 'dr')
                        ->sum('amount');

                    $sharer[$key]['total_dr'] = $total_dr;

                    $total_cr = $value->transaction_details()
                        ->where('date', '>=', $fromDate)
                        ->where('date', '<=', $toDate)
                        ->where('transaction_type', 'cr')
                        ->sum('amount');

                    $sharer[$key]['total_cr'] = $total_cr;

//                    $closing_balance = $value->account_head_balance()->latestAccountBalance($toDate)->first();


//                    $sharer[$key]['closing_balance'] = $closing_balance ? ($closing_balance->balance > 0 ? create_money_format($closing_balance->balance) . " Dr" : ($closing_balance->balance < 0 ? create_money_format($closing_balance->balance * (-1)) . " Cr" : "")) : "";
//
//                    $data['total_closing_balance'] += $closing_balance ? $closing_balance->balance : 0;

                    $closing_balance = $bf_balance + $total_dr - $total_cr;

                    $sharer[$key]['closing_balance'] = $closing_balance != 0 ? $closing_balance > 0 ? create_money_format($closing_balance) . " Dr" : create_money_format($closing_balance * (-1)) . " Cr" : "";

                    $data['total_closing_balance'] += $closing_balance;


                    $last_payment_date = $value->transaction_details()
                        ->orderBy('date', 'desc')
                        ->select('date')
                        ->first();


                    $sharer[$key]['last_payment_date'] = $last_payment_date ? $last_payment_date->date : "";
                }

            }


            $data['modal'] = $sharer;


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $managers = new User();
        $managers = $this->authCompany($managers, $request);
        $data['managers'] = $managers->get()->pluck('full_name', 'id');

        /* Area / place wise search */

        $data['divisions'] = Division::get()->pluck('display_name_bd', 'id');
        $data['districts'] = District::get()->pluck('display_name_bd', 'id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd', 'id');
        $data['unions'] = Union::get()->pluck('display_name_bd', 'id');
        $data['areas'] = Area::get()->pluck('name', 'id');

        /* Area / place wise search */

//dd($data);
        $data['report_title'] = $title = ucfirst($customer_type) . " Balance Report by Manager";
        $data['report_date'] = db_date_month_year_format($fromDate) . ($toDate ? " - " . db_date_month_year_format($toDate) : "");

        if ($request->type == "print" || $request->type == "download" || $request->type == "excel") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.print_sharer_balance_by_manager', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_sharer_balance_by_manager', $data);
                $file_name = file_name_generator($title);


                return $pdf->download($file_name . ".pdf");
            } else {
                $file_name = file_name_generator($title);
                $excel = new SharerBalanceByManagerExcel();
                return $excel->make($file_name, $data);
            }
        } else {

            return view('member.reports.sharer_balance_by_manager', $data);
        }

    }

    public function sharer_sales_report(Request $request)
    {
        $inputs = $request->all();
        $customer_type = strtolower($request->customer_type);


        if (empty($inputs['from_date'])) {
            $start = new Carbon('first day of this month');
            $inputs['from_date'] = $fromDate = db_date_format($start);
        } else {
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        }

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = db_date_format(Carbon::today());


        $data['is_blacklist'] = $data['skip_blacklist'] = $data['opening_balance'] = $data['total_dr_Cr'] = $is_blacklist = $skip_blacklist = '';

        if (!empty($inputs['opening_balance'])) {
            $data['opening_balance'] = $inputs['opening_balance'];
        }

//        if (!empty($inputs['is_blacklist'])) {
//            $data['is_blacklist'] = $is_blacklist = $inputs['is_blacklist'];
//        }
//
//        if (!empty($inputs['skip_blacklist'])) {
//            $data['skip_blacklist'] = $skip_blacklist = $inputs['skip_blacklist'];
//        }


        if (!empty($inputs['total_dr_Cr'])) {
            $data['total_dr_Cr'] = $inputs['total_dr_Cr'];
        }

        $data['total_closing_balance'] = 0;
        $data['modal'] = [];
        $data['customer_type'] = $customer_type;


        if (!empty($inputs['product_id'])) {

            $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->active();

            if (!empty($inputs['manager_id'])) {
                $manager_id = $inputs['manager_id'];
                $user = $data['manager_info'] = User::findOrFail($manager_id);
                $sharer = $sharer->where('manager_id', $manager_id);
            }

            if (isset($inputs['division_id'])) {
                $division_id = $inputs['division_id'];
                $sharer = $sharer->where('division_id', $division_id);
                $data['division'] = Division::find($division_id);
            }

            if (isset($inputs['district_id'])) {
                $district_id = $inputs['district_id'];
                $sharer = $sharer->where('district_id', $district_id);
                $data['district'] = District::find($district_id);
            }

            if (isset($inputs['upazilla_id'])) {
                $upazilla_id = $inputs['upazilla_id'];
                $sharer = $sharer->where('upazilla_id', $upazilla_id);
                $data['upazilla'] = Upazilla::find($upazilla_id);
            }

            if (isset($inputs['union_id'])) {
                $union_id = $inputs['union_id'];
                $sharer = $sharer->where('union_id', $union_id);
                $data['union'] = Union::find($union_id);
            }

            if (isset($inputs['area_id'])) {
                $area_id = $inputs['area_id'];
                $sharer = $sharer->where('area_id', $area_id);
                $data['area'] = Area::find($area_id);
            }
            if (isset($inputs['product_id'])) {
                $product_id = $inputs['product_id'];
                $data['product'] = $item = Item::find($product_id);
            }
//
//            if (!empty($is_blacklist)) {
//                $sharer = $sharer->where('is_blacklist', 1);
//            }
//
//            if (!empty($skip_blacklist)) {
//                $sharer = $sharer->where('is_blacklist', '!=', 1);
//            }
//
//            if ($customer_type == "customer") {
                $sharer = $sharer->onlyCustomers();
//            } else {
//                $sharer = $sharer->onlySuppliers();
//            }


            $sharer = $sharer->orderBy('name')->get();

            foreach ($sharer as $key => $value) {


                if ($value->account_type_id != null) {
                    $opening_balance = $value->account_head_balance()->previousAccountBalance($fromDate)->first();


                    $sharer[$key]['opening_balance'] = $bf_balance = $opening_balance ? $opening_balance->balance : 0;

                    $sales = DB::table('sales_details')
                        ->leftJoin('sales', 'sales.id', '=', 'sales_details.sale_id')
                        ->leftJoin('items', 'items.id', '=', 'sales_details.item_id')
                        ->where('customer_id', $value->id)
                        ->where('sales_details.date', '>=', $fromDate)
                        ->where('sales_details.date', '<=', $toDate)
                        ->where('sales_details.item_id', $product_id);

                    $sales = $this->authCompany($sales, $request, 'sales');

                    $sales =  $sales
//                        ->groupBy('sales_details.item_id')
//                        ->orderBy('sales_details.item_id')
                        ->select(
                            DB::raw('SUM(sales_details.total_price) as total_price'),
                            DB::raw('SUM(sales_details.qty) as total_qty'),
                            DB::raw('AVG(sales_details.price) as price')
                        )->first();

                    $sharer[$key]['item_name'] = $item->item_name;
                    $sharer[$key]['item_price'] = $sales ? $sales->price : '';
                    $sharer[$key]['item_total_qty'] = $sales ? $sales->total_qty : '';
                    $sharer[$key]['item_total_price'] = $sales ? $sales->total_price : '';

                    $total_dr = $value->transaction_details()
                        ->where('date', '>=', $fromDate)
                        ->where('date', '<=', $toDate)
                        ->where('transaction_type', 'cr')
                        ->sum('amount');

                    $sharer[$key]['total_dr'] = $total_dr;

                    $closing_balance = $value->account_head_balance()->latestAccountBalance($toDate)->first();


                    $sharer[$key]['closing_balance'] = $closing_balance ? ($closing_balance->balance > 0 ? create_money_format($closing_balance->balance) . " Dr" : ($closing_balance->balance < 0 ? create_money_format($closing_balance->balance * (-1)) . " Cr" : "")) : "";

                    $data['total_closing_balance'] += $closing_balance ? $closing_balance->balance : 0;



                    $last_payment_date = $value->transaction_details()
                        ->orderBy('date', 'desc')
                        ->select('date')
                        ->first();


                    $sharer[$key]['last_payment_date'] = $last_payment_date ? $last_payment_date->date : "";
                }

            }


            $data['modal'] = $sharer;
        }

//        dd($data['modal']);

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $managers = new User();
        $managers = $this->authCompany($managers, $request);
        $data['managers'] = $managers->get()->pluck('full_name', 'id');

        /* Area / place wise search */

        $data['divisions'] = Division::get()->pluck('display_name_bd', 'id');
        $data['districts'] = District::get()->pluck('display_name_bd', 'id');
        $data['upazillas'] = Upazilla::get()->pluck('display_name_bd', 'id');
        $data['unions'] = Union::get()->pluck('display_name_bd', 'id');
        $data['areas'] = Area::get()->pluck('name', 'id');
        $data['products'] = Item::get()->pluck('item_name', 'id');

        /* Area / place wise search */

//dd($data);
         $report_title = $title = "Customer Sales Report";
        $report_title = isset($data['manager_info'])  ? $report_title."<br/> Manager: ".$data['manager_info']->ucFullName." <br/> " : $report_title;
//        $report_title = $data['sharer'] ? $report_title.$data['sharer']." <br/> " : $report_title;
        $report_title = isset($data['area']) ? $report_title.$data['area']->name." <br/> " : $report_title;
        $report_title = isset($data['union']) ? $report_title." ".$data['union']->name : $report_title;
        $report_title = isset($data['upazilla']) ? $report_title.$data['upazilla']->name." <br/> " : $report_title;
        $report_title = isset($data['district'])  ? $report_title." ".$data['district']->name : $report_title;

        $data['report_title'] = $report_title;

        $data['report_date'] = db_date_month_year_format($fromDate) . ($toDate ? " - " . db_date_month_year_format($toDate) : "");

        if ($request->type == "print" || $request->type == "download" || $request->type == "excel") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return View('member.reports.sales.print_sharer_sales_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.sales.print_sharer_sales_report', $data);
                $file_name = file_name_generator($title);


                return $pdf->download($file_name . ".pdf");
            } else {
                $file_name = file_name_generator($title);
                $excel = new SharerBalanceByManagerExcel();
                return $excel->make($file_name, $data);
            }
        } else {
            return view('member.reports.sales.sharer_sales_report', $data);
        }

    }

    public function cash_flow(Request $request){
//        $inventories = new StockReport();
//
//        $inputs = $request->all();
//        $fromDate = $toDate = '';
//
//        if (!empty($inputs['from_date']) && !empty($inputs['to_date'])) {
//            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
//            $inputs['to_date'] = $toDate = $date = db_date_format($inputs['to_date']);
//        } else if (!empty($request->fiscal_year)) {
//
//            $fiscal_year = FiscalYear::find($request->fiscal_year);
//            $fromDate = $fiscal_year->start_date;
//            $toDate = $date = $fiscal_year->end_date;
//        } else if (!empty($request->year)) {
//
//            $fromDate = $request->year . "-01-01";
//            $in_date = $inventories->select('date')->orderby('date', 'desc')->whereYear('date', $request->year)->first();
//            $last_date = Transactions::select('date')->orderby('date', 'desc')->whereYear('date', $request->year);
//            $last_date = $last_date->first();
//            $date = $in_date ? $in_date->date : null;
//            $toDate = $last_date ? $last_date->date : null;
//        } else {
//            $from_date = Transactions::select('date')->orderby('date');
//            $from_date = $from_date->first();
//            $fromDate = $from_date ? $from_date->date : null;
//
//            $last_date = Transactions::select('date')->orderby('date', 'desc');
//            $last_date = $last_date->first();
//            $toDate = $last_date ? $last_date->date : null;
//
//        }
//
//        if (!$fromDate || !$toDate) {
//            $status = ['type' => 'danger', 'message' => 'There is no data in that date/year for Cash Flow'];
//            return redirect()->back()->with('status', $status);
//        }
//
//        $fiscal_year = new FiscalYear();
//        $cash_banks = CashOrBankAccount::withoutSupplierCustomer()->active()->pluck('account_type_id');
//
//        $first_fiscal_year = $fiscal_year->first();
//        $data['set_company_fiscal_year'] = $set_company_fiscal_year = Auth::user()->company->fiscal_year->first();
//
//        if($first_fiscal_year->id == $set_company_fiscal_year->id)
//        {
//            $data['opening_balance'] = TransactionDetail::whereIntegerInRaw('account_type_id', $cash_banks)
//                ->whereHas('transaction', function($query){
//                    $query->where('transaction_method', 'Initial');
//                })
//                ->sum('amount');
//        }else{
//            $fDate = $set_company_fiscal_year->start_date;
//            $tDate = $set_company_fiscal_year->end_date;
////            $data['opening_balance'] = TransactionDetail::whereIntegerInRaw('account_type_id', $cash_banks)
////                ->whereHas('transaction', function($query) use($set_company_fiscal_year){
////                    $query->where('fiscal_year_id', $set_company_fiscal_year->id);
////                })
////                ->sum('amount');
//        }
//
//        $data['fiscal_year'] = $fiscal_year->get()->pluck('title', 'id');
//        $data['fiscal_years'] = $fiscal_year->get();
//        $data['report_title'] = "Cash Flow Statement: " . db_date_month_year_format($fromDate) . ($toDate ? " - " . db_date_month_year_format($toDate) : "");
//        $data['companies'] = Company::get()->pluck('company_name', 'id');
//        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
//
//
//        if ($request->type == "print" || $request->type == "download") {
//
//            if ($request->type == "print") {
//                return view('member.reports.print_cash_flow', $data);
//            } else if ($request->type == "download") {
//                $pdf = PDF::loadView('member.reports.print_cash_flow', $data);
//                $file_name = file_name_generator($title);
//
//                return $pdf->download($file_name);
//            }
//
//        } else {
//            return view('member.reports.cash_flow', $data);
//        }
//
    }

    public function daily_sheet2(Request $request)
    {
        $inputs = $request->all();

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);
        else
            $inputs['from_date'] = $fromDate = Carbon::today();

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);
        else
            $inputs['to_date'] = $toDate = Carbon::today();

        $data['lastTotalDebit'] = $data['lastTotalCredit'] = 0;

        $supplier_or_customers = new SupplierOrCustomer();
        $supplier_or_customers = $supplier_or_customers->whereNotNull('cash_or_bank_id');
        $supplier_or_customers = $this->authCompany($supplier_or_customers, $request);
        $supplier_or_customers = $supplier_or_customers->get()->pluck('cash_or_bank_id');

        $cash_banks = CashOrBankAccount::whereIntegerNotInRaw('id', $supplier_or_customers);
        $cash_banks = $this->authCompany($cash_banks, $request);
        $cash_banks = $cash_banks->get()->pluck('account_type_id');


        $query = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->leftJoin('items', 'items.id', '=', 'purchase_details.item_id')
            ->select('transaction_details.id as transaction_details_id',
                'transactions.id as transactions_id',
                'transactions.transaction_method',
                'transaction_details.date',
                'transaction_details.transaction_type',
                'transaction_details.payment_method_id',
                'transaction_details.account_type_id',
                'items.item_name',
                'items.unit as item_unit',
                'transactions.sale_id',
                'transactions.purchase_id',
                'transaction_details.against_account_name',
                'transaction_details.description',
                'purchase_details.item_id as item_id',
                'purchase_details.qty as purchase_qty',
                'purchase_details.price as purchase_price',
                'purchase_details.total_price as purchase_total_price',
                'transaction_details.amount as td_amount',
                'account_types.display_name as account_name',
                'transaction_details.account_type_id',
                'transactions.company_id'
            )
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id');


        $debitAmountByHead = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->select('account_types.display_name as account_name', 'transaction_details.account_type_id', 'transaction_details.payment_method_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transaction_details.account_type_id', '!=', 35)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id')
            ->groupBy('transaction_details.account_type_id');

        $debitAmountByHeadByItem = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('purchase_details', 'purchase_details.purchase_id', '=', 'transactions.purchase_id')
            ->select('account_types.display_name as account_name', 'transaction_details.account_type_id', 'transaction_details.payment_method_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transactions.purchase_id', '>', 0)
            ->where('transaction_details.account_type_id', '==', 35)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'dr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('purchase_details.item_id')
            ->groupBy('transaction_details.account_type_id')
            ->groupBy('purchase_details.item_id');

        $sales = SaleDetails::select('item_id', DB::raw('SUM(total_price) as sale_total_price'), DB::raw('COUNT(*) as count'), DB::raw('SUM(qty) as sale_qty'))->where('sale_status', 'sale')->groupBy('item_id');

        $purchases = PurchaseDetail::select('item_id', DB::raw('SUM(total_price) as purchase_total_price'), DB::raw('COUNT(*) as count'), DB::raw('SUM(qty) as purchase_qty'))->groupBy('item_id');

        $bank_payment = PaymentMethod::where('name', 'Bank')->first();
        $cash_payment = PaymentMethod::where('name', 'Cash')->first();


        $query = $query->whereBetween('transactions.date', [$fromDate, $toDate]);
        $debitAmountByHead = $debitAmountByHead->whereBetween('transactions.date', [$fromDate, $toDate]);
        $debitAmountByHeadByItem = $debitAmountByHeadByItem->whereBetween('transactions.date', [$fromDate, $toDate]);


        $accountCash = AccountType::where('display_name', 'like', "%cash%")->authCompany()->pluck('id');
        $accountBank = AccountType::where('name', 'like', "bank")->authCompany()->pluck('id');

        $childAccountCash = AccountType::where('id', 4)->orWhereIn('parent_id', $accountCash);
        $childAccountCash = $this->authCompany($childAccountCash, $request);
        $childAccountCash = $childAccountCash->get()->pluck('id');

        $childAccountBank = AccountType::onlyBankAccount();
        $childAccountBank = $this->authCompany($childAccountBank, $request);
        $childAccountBank = $childAccountBank->get()->pluck('id');

        $banks = CashOrBankAccount::whereIntegerInRaw('account_type_id', $childAccountBank)->get()->pluck('account_type_id');

//        if (!empty($toDate) && empty($fromDate)) {
//            $firstDate = Transactions::select('date')->orderBy('date')->first();
//            $date = $fromDate ? $fromDate : $firstDate->date;
//
//        } elseif (!empty($fromDate)) {
//            $date = $fromDate;
//        } else {
//
//            $date = Carbon::today();
//        }
        $date = $toDate;

        $data['cash_banks'] = [];
        $data['cash_balance'] = 0;

        foreach ($accountCash as $key => $value) {
            $accountBalance = AccountHeadDayWiseBalance::where('account_type_id', $value)
                ->where('date', '<', $date)
                ->where('balance', '!=', 0)
                ->orderby('date', 'desc');

            $accountBalance = $this->authCompany($accountBalance, $request);
            $accountBalance = $accountBalance->first();

            if ($accountBalance) {
                $data['cash_banks'][$key]['account_type_id'] = $value;
                $data['cash_banks'][$key]['account_type_name'] = $accountBalance->account_types->display_name;
                $data['cash_banks'][$key]['balance'] = $accountBalance->balance;
                $data['cash_balance'] += $accountBalance->balance;
            }
        }


        $data['transaction_method'] = isset($inputs['transaction_method']) ? $inputs['transaction_method'] : "";

        if (!empty($inputs['transaction_method'])) {
            if ($inputs['transaction_method'] == "due") {

                $query = $query->where('transaction_details.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHead = $debitAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);
                $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);

            } elseif ($inputs['transaction_method'] == "cash") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $childAccountCash)->where('amount', '>', 0)->where('date', $date)->get()->pluck('transaction_id');

                $query = $query->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])->where('transaction_details.payment_method_id', $cash_payment->id);

                $debitAmountByHead = $debitAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])->where('transaction_details.payment_method_id', $cash_payment->id);

                $debitAmountByHeadByItem = $debitAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases'])->where('transaction_details.payment_method_id', $cash_payment->id);

            } elseif ($inputs['transaction_method'] == "bank") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $banks)->orderBy('transaction_id', 'desc')->get()->pluck('transaction_id');

                $query = $query->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

                $debitAmountByHead = $debitAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

                $debitAmountByHeadByItem = $debitAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

            } else {

                $query = $query->where('transactions.transaction_method', '=', $inputs['transaction_method']);

                $debitAmountByHead = $debitAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);

                $debitAmountByHeadByItem = $debitAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);
            }

        }


        $query2 = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->leftJoin('items', 'items.id', '=', 'sales_details.item_id')
            ->select('transactions.transaction_method',
                'transaction_details.date',
                'transaction_details.transaction_type',
                'transaction_details.account_type_id',
                'items.item_name', 'items.unit as item_unit',
                'transaction_details.id as transaction_details_id',
                'transactions.id as transactions_id',
                'transactions.sale_id',
                'transactions.purchase_id',
                'transaction_details.against_account_name',
                'transaction_details.description',
                'sales_details.item_id as item_id',
                'sales_details.qty as sale_qty',
                'sales_details.id as sales_details_id',
                'sales_details.price as sale_price',
                'sales_details.total_price as sale_total_price',
                'transaction_details.amount as td_amount',
                'transaction_details.account_type_id',
                'account_types.display_name as account_name'
            )
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id');


        $creditAmountByHead = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->select(
                'account_types.display_name as account_name', 'transaction_details.account_type_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transaction_details.account_type_id', '!=', 18)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id')
            ->groupBy('transaction_details.account_type_id');

        $creditAmountByHeadByItem = DB::table('transactions')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('sales_details', 'sales_details.sale_id', '=', 'transactions.sale_id')
            ->select('account_types.display_name as account_name', 'transaction_details.account_type_id',
                DB::raw('SUM(transaction_details.amount) as transaction_amount'),
                DB::raw('COUNT(transaction_details.id) as transaction_count')
            )
            ->where('transactions.sale_id', '>', 0)
            ->where('transaction_details.account_type_id', '==', 18)
            ->where('transaction_details.account_type_id', '!=', 4)
            ->where('transaction_details.amount', '>', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_type', '=', 'cr')
            ->orderBy('transaction_details.account_type_id')
            ->orderBy('sales_details.item_id')
            ->groupBy('transaction_details.account_type_id')
            ->groupBy('sales_details.item_id');


        if (!empty($inputs['transaction_method'])) {
            if ($inputs['transaction_method'] == "due") {

                $query2 = $query2->where('transaction_details.transaction_method', '=', $inputs['transaction_method']);

                $creditAmountByHead = $creditAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);

                $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);

            } elseif ($inputs['transaction_method'] == "cash") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $childAccountCash)->where('amount', '>', "0")->where('date', $date)->get()->pluck('transaction_id');

                $query2 = $query2->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);
                $creditAmountByHead = $creditAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);

                $creditAmountByHeadByItem = $creditAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Purchases']);


            } elseif ($inputs['transaction_method'] == "bank") {

                $transaction_id = TransactionDetail::whereIntegerInRaw('account_type_id', $banks)->where('amount', '>', "0")->get()->pluck('transaction_id');

                $query2 = $query2->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

                $creditAmountByHead = $creditAmountByHead->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

                $creditAmountByHeadByItem = $creditAmountByHeadByItem->whereIntegerInRaw('transactions.id', $transaction_id)->whereNotIn('transactions.transaction_method', ['Sales', 'Purchases'])->where('transaction_details.payment_method_id', $bank_payment->id);

            } else {

                $query2 = $query2->where('transactions.transaction_method', '=', $inputs['transaction_method']);

                $creditAmountByHead = $creditAmountByHead->where('transactions.transaction_method', '=', $inputs['transaction_method']);

                $creditAmountByHeadByItem = $creditAmountByHeadByItem->where('transactions.transaction_method', '=', $inputs['transaction_method']);

            }

        }

        $transaction_sales = Transactions::whereBetween('transactions.date', [$fromDate, $toDate])->get()->pluck('sale_id');
        $transaction_purchases = Transactions::whereBetween('transactions.date', [$fromDate, $toDate])->get()->pluck('purchase_id');

        $query2 = $query2->whereBetween('transactions.date', [$fromDate, $toDate]);

        $creditAmountByHead = $creditAmountByHead->whereBetween('transactions.date', [$fromDate, $toDate]);

        $creditAmountByHeadByItem = $creditAmountByHeadByItem->whereBetween('transactions.date', [$fromDate, $toDate]);

        $sales = $sales->whereHas('sale', function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        });

        $purchases = $purchases->whereHas('purchases', function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        });


        $daily_sheet_title = "";
        $transaction_method = !empty($inputs['transaction_method']) ? strtolower($inputs['transaction_method']) : "";

        if ($transaction_method == "cash") {
            $daily_sheet_title = "Cash Book";
        } elseif ($transaction_method == "bank") {
            $daily_sheet_title = "Bank Book";
        } elseif ($transaction_method == "sale") {
            $daily_sheet_title = "Sale Book";
        } elseif ($transaction_method == "purchase") {
            $daily_sheet_title = "Purchase Book";
        }

        $data['daily_sheet_title'] = $daily_sheet_title;
        $data['title'] = $data['report_title'] = $transaction_method == "bank" ? 'Daily Cheque Sheet' : 'Daily Sheet';
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['report_date'] = db_date_month_year_format($fromDate) . " to " . db_date_month_year_format($toDate);


        $table = 'transactions';
        $query = $this->authCompany($query, $request, $table);
        $sales = $this->authCompany($sales, $request);
        $purchases = $this->authCompany($purchases, $request);
        $debitAmountByHead = $this->authCompany($debitAmountByHead, $request, $table);
        $debitAmountByHeadByItem = $this->authCompany($debitAmountByHeadByItem, $request, $table);
        $query2 = $this->authCompany($query2, $request, $table);
        $creditAmountByHead = $this->authCompany($creditAmountByHead, $request, $table);
        $creditAmountByHeadByItem = $this->authCompany($creditAmountByHeadByItem, $request, $table);

        $data['transaction_method'] = !empty($inputs['transaction_method']) ? strtolower($inputs['transaction_method']) : "";


        $data['sales'] = $sales->whereIntegerInRaw('sale_id', $transaction_sales)->get();
        $data['purchases'] = $purchases->whereIntegerInRaw('purchase_id', $transaction_purchases)->get();
        $data['debits'] = $query->get();
        $data['debitAmountByHead'] = $debitAmountByHead->get();
        $data['debitAmountByHeadByItem'] = $debitAmountByHeadByItem->whereIntegerInRaw('transactions.purchase_id', $transaction_purchases)->get();
        $data['credits'] = $query2->get();
        $data['creditAmountByHead'] = $creditAmountByHead->get();
        $data['creditAmountByHeadByItem'] = $creditAmountByHeadByItem->whereIntegerInRaw('transactions.sale_id', $transaction_sales)->get();

//        dd($data);
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");


        if ($request->type == "print" || $request->type == "download" || $request->type == "single_print") {
            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.print_daily_sheet', $data);
            } else if ($request->type == "single_print") {
                return view('member.reports.dual_print_daily_sheet', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.print_daily_sheet', $data);
                $file_name = file_name_generator("Daily_sheet_Report_");

                return $pdf->download($file_name . ".pdf");
            } else if ($request->type == "excel") {

                $file_name = file_name_generator("Daily_sheet_Report_");

                $excel = new \DailySheetExport();
                $fileExcel = $excel->make($file_name, $data);
                return Excel::download($fileExcel, $file_name . ".xlsx");
            }
        } else {
            return view('member.reports.daily_sheet_by_per_price', $data);
        }

    }

    public function total_sales_purchases_report(Request $request)
    {

        $inputs = $request->all();

        $sale_details = new SaleDetails();
        $sale_details = $this->searchInventoryDate($inputs, $sale_details);
        $sale_details = $this->authCompany($sale_details, $request);

        $data['sale_details'] = $sale_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->orderBy('item_id')
            ->get();


        $purchase_details = new PurchaseDetail();
        $purchase_details = $this->authCompany($purchase_details, $request);
        $purchase_details = $this->searchInventoryDate($inputs, $purchase_details);

        $data['purchase_details'] = $purchase_details->groupBy('item_id')
            ->selectRaw('*, sum(qty) as total_qty, sum(total_price) as sum_total_price')
            ->orderBy('item_id')
            ->get();

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.total_sales_and_purchases_report');
        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['from_date'] = $inputs['from_date'] ?? null;
        $data['to_date'] = $inputs['to_date'] ?? null;

        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.summary.print_total_sales_purchases', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.summary.print_total_sales_purchases', $data);
                $file_name = file_name_generator("Total_Sales_and_Purchases_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.summary.total_sales_purchases', $data);
        }

    }

    public function balance_profit(Request $request){

        $inputs = $request->all();

        $sale_details = new Sale();
        $sale_details = $this->authCompany($sale_details, $request);
        $sale_details = $this->searchInventoryDate($inputs, $sale_details);

        $data['total_sale'] = $sale_details->sum('paid_amount');
        $data['sale_discount'] = $sale_details->sum('total_discount');
        $data['sale_due'] = $sale_details->sum('due');


        $purchase_details = new Purchase();
        $purchase_details = $this->authCompany($purchase_details, $request);
        $purchase_details = $this->searchInventoryDate($inputs, $purchase_details);

        $data['total_purchase'] = $purchase_details->sum('paid_amount');
        $data['purchase_discount'] = $purchase_details->sum('total_discount');
        $data['purchase_due'] = $purchase_details->sum('due_amount');

        $sale_return = new SaleReturn();
        $sale_return = $this->authCompany($sale_return, $request);

        $purchase_return = new ReturnPurchase();
        $purchase_return = $this->authCompany($purchase_return, $request);

        $fromDate = $toDate = '';
        if( !empty($inputs['from_date']) )
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if( !empty($inputs['to_date']) )
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if( empty($fromDate) && (!empty($toDate)) ) {
            $sale_return = $sale_return->whereDate('created_at','<=', $toDate);
            $purchase_return = $purchase_return->whereDate('created_at','<=', $toDate);
        }elseif( (!empty($fromDate)) && empty($toDate) ) {
            $sale_return = $sale_return->whereDate('created_at','>=', $fromDate);
            $purchase_return = $purchase_return->whereDate('created_at','>=', $fromDate);
        }elseif ( $fromDate !='' && $toDate != '' ) {
            $sale_return = $sale_return->whereBetween('created_at', [$fromDate, $toDate]);
            $purchase_return = $purchase_return->whereBetween('created_at', [$fromDate, $toDate]);
        }else{
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $sale_return = $sale_return->whereDate('created_at', '>=', $fromDate);
            $purchase_return = $purchase_return->whereDate('created_at', '>=', $fromDate);
        }


        $data['total_sale_return'] = $sale_return->sum(DB::raw('return_qty * return_price'));
        $data['total_purchase_return'] = $purchase_return->sum(DB::raw('return_qty * return_price'));


        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");
        $data['report_title'] = trans('common.balance_profit')." <br/> " . db_date_month_year_format($fromDate) . " to " .($toDate ?  db_date_month_year_format($toDate) : db_date_month_year_format(Carbon::today()));



        if ($request->type == "print" || $request->type == "download") {
            $data = $this->company($data);

            if ($request->type == "print") {
                return View('member.reports.summary.print_balance_profit', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.summary.print_balance_profit', $data);
                $file_name = file_name_generator("Balance_profit_Report_");

                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.reports.summary.balance_profit', $data);
        }

    }

    private function searchInventoryDate($inputs, $query)
    {

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if (empty($fromDate) && (!empty($toDate))) {
            $query = $query->whereDate('date', '<=', $toDate);
        } elseif ((!empty($fromDate)) && empty($toDate)) {
            $query = $query->whereDate('date', '>=', $fromDate);
        } elseif ($fromDate != '' && $toDate != '') {
            $query = $query->whereBetween('date', [$fromDate, $toDate]);
        } else {
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate('date', '>=', $fromDate);
        }

        return $query;

    }

    private function childAccountSearch($id)
    {
        $parentAccount = AccountType::where('parent_id', $id)->pluck('id');

        return $parentAccount;
    }


}
