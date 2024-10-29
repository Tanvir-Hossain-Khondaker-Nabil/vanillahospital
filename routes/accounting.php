<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'language'], function () {


    Route::group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'as' => 'admin.'
    ], function () {

        Route::group([
            'middleware' => ['auth', 'role:developer|admin|super-admin'],
        ], function () {


            /*
             *  Start AccountTypeController
             *  All Ledgers Manage by This Controller
             */

            Route::resource('account_types', 'AccountTypeController');
            Route::delete('account-type-force-delete', ['as' => 'account_types.force_delete', 'uses' => 'AccountTypeController@forcedelete']);
            Route::get('account-group', ['as' => 'account_types.group', 'uses' => 'AccountTypeController@group']);
            Route::get('account-heads', ['as' => 'account_types.heads', 'uses' => 'AccountTypeController@heads']);
            Route::get('account-sub-heads', ['as' => 'account_types.sub_heads', 'uses' => 'AccountTypeController@sub_heads']);

            Route::resource('payment_methods', 'PaymentMethodController');
            Route::resource('transaction_categories', 'TransactionCategoryController');
            Route::get('expense-transaction-categories', ['as' => 'transaction_categories.expense', 'uses' => 'TransactionCategoryController@expense_category_list']);
            Route::get('income-transaction-categories', ['as' => 'transaction_categories.income', 'uses' => 'TransactionCategoryController@income_category_list']);


            Route::get('stock-reconcile/{id}',['as' => 'stock_reconcile', 'uses' =>'StockController@stock_reconcile']);
            Route::post('update-damage-stock',['as' => 'damage_stock_update', 'uses' =>'StockController@damage_stock_update']);
            Route::get('yearly-stock-reconcile',['as' => 'yearly_stock_reconcile', 'uses' =>'StockController@yearly_stock_reconcile']);
            Route::post('update-stock-reconcile',['as' => 'update_stock_reconcile', 'uses' =>'StockController@update_stock_reconcile']);
            Route::post('update-stock-overflow-reconcile',['as' => 'update_stock_overflow_reconcile', 'uses' =>'StockController@update_stock_overflow_reconcile']);


        });
    });


    Route::group([
        'prefix' => 'member',
        'namespace' => 'Member',
        'as' => 'member.'
    ], function () {


        Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

        Route::group([
            'middleware' => ['auth'],
        ], function () {

            Route::get('print-barcode-form', ['as' => 'items.print_barcode_form', 'uses' => 'ItemController@print_barcode_form']);
            Route::get('print-barcode', ['as' => 'items.print_barcode', 'uses' => 'ItemController@print_barcode']);

            Route::resource('cash_or_bank_accounts', 'CashBankAccountController');
            Route::get('bank-gl-account', ['as' => 'cash_or_bank_accounts.bank_gl_account', 'uses' => 'CashBankAccountController@bank_gl_account']);

            Route::resource('company', 'CompanyController');

            Route::get('company-set-feature', ['as' => 'company.feature', 'uses' => 'CompanyController@feature']);
            Route::post('company-store-feature', ['as' => 'company.feature_store', 'uses' => 'CompanyController@feature_store']);

            // This Customer Only For Dealer's Customer

            Route::resource('customers','CustomerController');

            /*
             * Start Sharer Controller
             *  Here Sharer Means:  Customer/ Supplier
             *  Sharer Manage Customers/Supplier in ONE Controller and Model
             */

            Route::resource('sharer', 'SharerController');
            Route::get('sharer/create/{type}', ['as' => 'sharer.create', 'uses' => 'SharerController@create']);
            Route::get('customer-list', ['as' => 'sharer.customer_list', 'uses' => 'SharerController@customer_list']);
            Route::get('supplier-list', ['as' => 'sharer.supplier_list', 'uses' => 'SharerController@supplier_list']);


            // Start Transaction Controller
            Route::resource('transaction', 'TransactionController');
            Route::get('manage-daily-sheet', ['as' => 'transaction.manage_daily_sheet', 'uses' => 'TransactionController@manage_daily_sheet']);
            Route::get('transaction/create/{type}', ['as' => 'transaction.create', 'uses' => 'TransactionController@create']);
            Route::post('transaction/income', ['as' => 'transaction.incomeStore', 'uses' => 'TransactionController@incomeStore']);
            Route::get('transaction/transfer/create', ['as' => 'transaction.transfer.create', 'uses' => 'TransactionController@transfer']);
            Route::post('transaction/transfer/save', ['as' => 'transaction.transfer.save', 'uses' => 'TransactionController@saveTransfer']);
            Route::get('transaction/transfer/list', ['as' => 'transaction.transfer.list', 'uses' => 'TransactionController@listTransfer']);


            // Start General Ledger Controller
            Route::get('general-ledgers', ['as' => 'general_ledger.search', 'uses' => 'GeneralLedgerController@search']);
            Route::get('general-ledger/print/{code}', ['as' => 'general_ledger.print', 'uses' => 'GeneralLedgerController@print_gl']);
            Route::resource('general_ledger', 'GeneralLedgerController');


            Route::post('account-type-save', ['as' => 'account_type.save', 'uses' => 'CommonController@saveAccountType']);
            Route::post('customer-save', ['as' => 'customer.save', 'uses' => 'CommonController@saveCustomer']);
            Route::post('payer-search', ['as' => 'payer.search', 'uses' => 'CommonController@payerSearch']);


            // ----------- All Reports --------------


            Route::group([
                'prefix' => 'report',
                'as' => 'report.',
                'namespace' => 'Reports'
            ], function () {

                Route::get('list', ['as' => 'list', 'uses' => 'ReportController@report_list']);
                Route::get('all-transactions', ['as' => 'all_transaction', 'uses' => 'ReportController@all_transaction']);
                Route::get('all-income', ['as' => 'all_income', 'uses' => 'ReportController@all_income']);
                Route::get('all-report-list/{type}', ['as' => 'all_report_list', 'uses' => 'ReportController@all_report_list']);
                Route::get('all-report-print/{type}', ['as' => 'all_report_print', 'uses' => 'ReportController@all_report_print']);
                //        Route::get('all-transaction-list', ['as' => 'all_transaction_list', 'uses' => 'ReportController@all_report_list']);
                //        Route::get('all-transaction-print', ['as' => 'all_transaction_print', 'uses' => 'ReportController@all_income_print']);
                Route::get('all-income-datatable', ['as' => 'all_income_datatable', 'uses' => 'ReportController@all_income_datatable']);
                Route::get('all-expense', ['as' => 'all_expense', 'uses' => 'ReportController@all_expense']);
                Route::get('all-transfer', ['as' => 'all_transfer', 'uses' => 'ReportController@all_transfer']);
                Route::get('all-journal-entry', ['as' => 'all_journal_entry', 'uses' => 'ReportController@all_journal_entry']);



                /*
                 *   Summary Report Start
                 *   All Summary Report Manage by SummaryReportController
                 */

                Route::get('print-fixed-assets', ['as' => 'fixed_asset_report', 'uses' => 'AccountBalanceReportControllerV2@fixed_asset_report']);
                Route::get('print-current-assets', ['as' => 'current_asset_report', 'uses' => 'AccountBalanceReportControllerV2@current_asset_report']);
                Route::get('print-cash-bank', ['as' => 'cash_bank_report', 'uses' => 'AccountBalanceReportControllerV2@cash_bank_report']);
                Route::get('print-bank-overdraft', ['as' => 'bank_overdraft_report', 'uses' => 'AccountBalanceReportControllerV2@bank_overdraft_report']);
                Route::get('print-trade-debtor', ['as' => 'trade_debtor_report', 'uses' => 'AccountBalanceReportControllerV2@trade_debtor_report']);
                Route::get('print-advance_prepayment_report', ['as' => 'advance_prepayment_report', 'uses' => 'AccountBalanceReportControllerV2@advance_prepayment_report']);
                Route::get('print-fixed_deposits_receipts_report', ['as' => 'fixed_deposits_receipts_report', 'uses' => 'AccountBalanceReportControllerV2@fixed_deposits_receipts_report']);
                Route::get('print-due_companies_report', ['as' => 'due_companies_report', 'uses' => 'AccountBalanceReportControllerV2@due_companies_report']);
                Route::get('print-income_report', ['as' => 'income_report', 'uses' => 'AccountBalanceReportControllerV2@income_report']);
                Route::get('print-expense_report', ['as' => 'expense_report', 'uses' => 'AccountBalanceReportControllerV2@expense_report']);
                Route::get('print-sundry_creditor_report', ['as' => 'sundry_creditor_report', 'uses' => 'AccountBalanceReportControllerV2@sundry_creditor_report']);
                Route::get('print-account_payable_report', ['as' => 'account_payable_report', 'uses' => 'AccountBalanceReportControllerV2@account_payable_report']);
                Route::get('print-account_receivable_report', ['as' => 'account_receivable_report', 'uses' => 'AccountBalanceReportControllerV2@account_receivable_report']);
                Route::get('print-equity_report', ['as' => 'equity_report', 'uses' => 'AccountBalanceReportControllerV2@equity_report']);
                Route::get('print-long_term_liability_report', ['as' => 'long_term_liability_report', 'uses' => 'AccountBalanceReportControllerV2@long_term_liability_report']);
                Route::get('print-due_to_affiliated_company_report', ['as' => 'due_to_affiliated_company_report', 'uses' => 'AccountBalanceReportControllerV2@due_to_affiliated_company_report']);
                Route::get('print-liability_for_expense_report', ['as' => 'liability_for_expense_report', 'uses' => 'AccountBalanceReportControllerV2@liability_for_expense_report']);
                Route::get('print-income_tax_payable_report', ['as' => 'income_tax_payable_report', 'uses' => 'AccountBalanceReportControllerV2@income_tax_payable_report']);
                Route::get('print-cost_of_sold_report', ['as' => 'cost_of_sold_report', 'uses' => 'AccountBalanceReportControllerV2@cost_of_sold_report']);
                Route::get('print-sales_report', ['as' => 'head_sales_report', 'uses' => 'InventoryReportController@sale_details']);
                Route::get('print-purchases_report', ['as' => 'head_purchases_report', 'uses' => 'InventoryReportController@purchase_details']);
                Route::get('print-sales-return-report', ['as' => 'head_sales_return_report', 'uses' => 'InventoryReportController@sale_return_details']);
                Route::get('print-purchases-return-report', ['as' => 'head_purchases_return_report', 'uses' => 'InventoryReportController@purchase_return_details']);
                Route::get('print-inventory-report', ['as' => 'head_inventory', 'uses' => 'InventoryReportController@index']);

                Route::get('print-current_liability_report', ['as' => 'current_liability_report', 'uses' => 'AccountBalanceReportControllerV2@current_liability_report']);
                Route::get('print-current_liability_report', ['as' => 'current_liability_report', 'uses' => 'AccountBalanceReportControllerV2@current_liability_report']);



                Route::get('trail-balance', ['as' => 'trail_balance', 'uses' => 'SummaryReportController@trail_balance']);
                Route::get('trail-balance-v2', ['as' => 'trail_balance_v2', 'uses' => 'SummaryReportController@trail_balance_v2']);
                Route::get('ledger-book', ['as' => 'ledger_book', 'uses' => 'SummaryReportController@ledger_book']);
            Route::get('daily-sheet-v1', ['as' => 'daily_sheet', 'uses' => 'SummaryReportController@daily_sheet']);
                Route::get('daily-sheet', ['as' => 'daily_sheet', 'uses' => 'SummaryReportController@daily_sheet2']);
//            Route::get('balance-sheet', ['as' => 'balance_sheet', 'uses' => 'SummaryReportController@balance_sheet']);
//             Route::get('balance-sheet', ['as' => 'balance_sheet', 'uses' => 'BalanceSheetController@index']);
                Route::get('balance-sheet', ['as' => 'balance_sheet', 'uses' => 'BalanceSheetControllerV2@index']);
                Route::get('balance-sheet-v3', ['as' => 'balance_sheet_v3', 'uses' => 'BalanceSheetControllerV3@index']);
//            Route::get('profit-and-loss', ['as' => 'lost_profit', 'uses' => 'SummaryReportController@lost_profit']);
//            Route::get('profit-and-loss', ['as' => 'lost_profit', 'uses' => 'ProfitLossReportController@index']);
                Route::get('profit-and-loss', ['as' => 'lost_profit', 'uses' => 'ProfitLossReportControllerV2@index']);
                Route::get('profit-and-loss-v3', ['as' => 'lost_profit_v3', 'uses' => 'ProfitLossReportControllerV3@index']);
                Route::get('sharer-balance', ['as' => 'sharer_balance_report', 'uses' => 'SummaryReportController@sharer_balance_report']);
                Route::get('ledger-balance', ['as' => 'ledger_balance_report', 'uses' => 'SummaryReportController@ledger_balance_report']);
                Route::get('cash-flow-statement', ['as' => 'cash_flow', 'uses' => 'SummaryReportController@cash_flow']);


                Route::get('account-day-wise-last-balance', ['as' => 'account_day_wise_last_balance', 'uses' => 'ReportController@account_day_wise_last_balance']);

            });

            // Start Journal Entry Controller
            Route::get('journal-entry/print/{code}', ['as' => 'journal_entry.print', 'uses' => 'JournalEntryController@print_journal_entry']);
            Route::resource('journal_entry', 'JournalEntryController');
            // End Journal Entry Controller

        });


        Route::resource('banks', 'BankController');
        Route::resource('cheque_entries', 'ChequeEntryController');
        Route::resource('reconciliation', 'ReconciliationController');
        Route::get('reconciliation/create/{type}', ['as' => 'reconciliation.create', 'uses' => 'ReconciliationController@create']);
        Route::get('today-cheque-list', ['as' => 'cheque_entries.today_cheque_list', 'uses' => 'ChequeEntryController@chequeTodaysQueue']);
        Route::post('cheque-status-change', ['as' => 'cheque_entries.change_status', 'uses' => 'ChequeEntryController@changeChequeStatus']);

    });


});

