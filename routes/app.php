<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 12/24/2020
 * Time: 5:43 PM
 */


Route::get('generate-key', function() {
    Artisan::call('key:generate');
    dd(' Generate key successfully');
});

Route::get('create-symlink', function() {
    Artisan::call('storage:link');
    dd(' Storage Link successfully');
});

Route::get('db-migrate-seed', function() {
    Artisan::call('migrate');
    Artisan::call('db:seed');
    dd('Migration and seed run successfully');
});

Route::get('db-seed', function() {
    Artisan::call('db:seed');
    dd('Seeder run successfully');
});


Route::get('migrate', function() {
    Artisan::call('migrate');
    dd('Migration run successfully');
});

Route::get('route-list', function() {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
//        echo "<td>" . $value->methods()[1] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});

Route::get('permission-route','Admin\PermissionController@generate_permission');
Route::get('generate-attendance','Member\AttendanceController@generate_attendance');

Route::get('cache-config', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    dd('Configuration cache cleared!');
});


Route::group(['middleware' => 'language'], function () {

    Route::group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'as' => 'admin.'
    ], function () {

        Route::group([
            'middleware' => ['auth', 'role:developer|admin|super-admin'],
        ], function () {

            Route::post('update_daily_cash_balance',['as' => 'ledger_book.update_daily_cash_balance', 'uses' =>'TransactionController@updateDailyCashBalance']);
            Route::get('set-account-head-balance',['as' => 'ledger_book.set_account_head_balance', 'uses' =>'TransactionController@setAccountHeadBalance']);
            Route::post('update-account-head-balance',['as' => 'ledger_book.update_account_head_balance', 'uses' =>'TransactionController@updateAccountHeadBalance']);
            Route::post('delete_account_head_daywise_balance',['as' => 'ledger_book.delete_account_head_daywise_balance', 'uses' =>'TransactionController@deleteAccountDayWiseBalance']);
            Route::get('update-all-account-head-balance',['as' => 'ledger_book.update_all_account_head_balance', 'uses' =>'TransactionController@updateAllAccountHeadBalance']);
            Route::get('get-update-stock-report',['as' => 'get_update_stock_report', 'uses' =>'StockController@get_update_stock_report']);

            Route::post('stock-report-update',['as' => 'stock_report_update', 'uses' =>'StockController@stock_report_update']);
            Route::post('update-stock-report',['as' => 'update_stock_report', 'uses' =>'StockController@update_stock_report']);
            Route::post('warehouse-update-stock-report',['as' => 'warehouse_update_stock_report', 'uses' =>'StockController@warehouse_stock_report_update']);
            Route::get('check-and-update-stock-report',['as' => 'check_update_stock_report', 'uses' =>'StockController@check_and_update_full_daily_stock']);
            Route::delete('delete-stock-delete/{id}',['as' => 'daily_stock.delete', 'uses' =>'StockController@daily_stock_delete']);
            Route::get('transaction-errors', ['as'=>'transactions-error.index', 'uses'=>'TransactionController@transactionNotComplete']);

        });
    });
});
