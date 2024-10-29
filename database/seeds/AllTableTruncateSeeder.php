<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllTableTruncateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('permission_role')->truncate();
        \App\Models\Permission::truncate();
        DB::table('role_user')->truncate();
        \App\Models\Role::truncate();
        \App\Models\User::truncate();
        DB::table('countries')->truncate();
        DB::table('divisions')->truncate();
        DB::table('districts')->truncate();
        DB::table('upazillas')->truncate();
        DB::table('unions')->truncate();
        \App\Models\Member::truncate();
        \App\Models\Membership::truncate();
        \App\Models\PaymentMethod::truncate();
        \App\Models\TransactionCategory::truncate();
        \App\Models\GLAccountClass::truncate();
        \App\Models\AccountType::truncate();
        \App\Models\Company::truncate();
        \App\Models\CashOrBankAccount::truncate();
        \App\Models\SupplierPurchases::truncate();
        \App\Models\SupplierOrCustomer::truncate();
        \App\Models\FiscalYear::truncate();
        \App\Models\DeliveryType::truncate();
        \App\Models\Category::truncate();
        \App\Models\Item::truncate();
        \App\Models\Purchase::truncate();
        \App\Models\PurchaseDetail::truncate();
        \App\Models\ReturnPurchase::truncate();
        \App\Models\Sale::truncate();
        \App\Models\SaleDetails::truncate();
        \App\Models\SaleReturn::truncate();
        \App\Models\Transactions::truncate();
        \App\Models\TransactionDetail::truncate();
        \App\Models\TransactionHistory::truncate();
        \App\Models\Stock::truncate();
        \App\Models\StockReport::truncate();
        \App\Models\StockHistory::truncate();
        \App\Models\Area::truncate();
        \App\Models\Unit::truncate();
        \App\Models\AccountHeadsBalanceHistory::truncate();
        \App\Models\TrackAccountHeadBalance::truncate();
        \App\Models\AccountHeadDayWiseBalance::truncate();
        \App\Models\TrackShoppingBags::truncate();
        \App\Models\Bank::truncate();
        \App\Models\DocumentType::truncate();
        \App\Models\Warehouse::truncate();
        \App\Models\WarehouseHistory::truncate();
        \App\Models\WarehouseStock::truncate();
        \App\Models\WarehouseStockHistory::truncate();
        \App\Models\WarehouseStockReport::truncate();
        \App\Models\Quotation::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
