<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/10/2019
 * Time: 2:50 PM
 */

namespace App\Http\Traits;


use App\Models\AccountType;
use App\Models\Area;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\CashOrBankAccount;
use App\Models\Category;
use App\Models\ChequeEntry;
use App\Models\Company;
use App\Models\Customer;
use App\Models\DealerSale;
use App\Models\DealerStock;
use App\Models\DueCollectionHistory;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\JournalEntryDetail;
use App\Models\LossStockReconcile;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Purchase;
use App\Models\Reconciliation;
use App\Models\Requisition;
use App\Models\ReturnPurchase;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SalesRequisition;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\StockOverflowReconcile;
use App\Models\SupplierOrCustomer;
use App\Models\SupplierPurchases;
use App\Models\TrackShoppingBags;
use App\Models\TransactionDetail;
use App\Models\TransactionHistory;
use App\Models\Transactions;
use App\Models\Unit;
use App\Models\User;
use App\Observers\AccountTypesObserver;
use App\Observers\AreaObserver;
use App\Observers\BankObserver;
use App\Observers\BranchObserver;
use App\Observers\BrandObserver;
use App\Observers\CashAndBankAccountObserver;
use App\Observers\CategoryObserver;
use App\Observers\ChequeEntryObserver;
use App\Observers\CompanyObserver;
use App\Observers\CustomerObserver;
use App\Observers\DealerSaleObserver;
use App\Observers\DealerStockObserver;
use App\Observers\DueCollectionHistoryObserver;
use App\Observers\FiscalYearObserver;
use App\Observers\ItemObserver;
use App\Observers\JournalEntryDetailsObserver;
use App\Observers\LossStockObserver;
use App\Observers\MemberObserver;
use App\Observers\MembershipObserver;
use App\Observers\PurchaseObserver;
use App\Observers\ReconciliationObserver;
use App\Observers\RequisitionObserver;
use App\Observers\ReturnPurchaseObserver;
use App\Observers\SaleObserver;
use App\Observers\SaleReturnObserver;
use App\Observers\SalesRequisitionObserver;
use App\Observers\StockHistoryObserver;
use App\Observers\StockObserver;
use App\Observers\StockOverflowObserver;
use App\Observers\SupplierOrCustomerObserver;
use App\Observers\SupplierPurchaseObserver;
use App\Observers\TrackShoppingBagsObserver;
use App\Observers\TransactionDetailsObserver;
use App\Observers\TransactionHistoryObserver;
use App\Observers\TransactionObserver;
use App\Observers\UnitObserver;
use App\Observers\UserObserver;

Trait ObserverTrait
{
    /**
     * Get model observers
     */
    public function getObservers()
    {
        // Model Observers
        User::observe(UserObserver::class);
        Member::observe(MemberObserver::class);
        Membership::observe(MembershipObserver::class);
        Company::observe(CompanyObserver::class);
        SupplierOrCustomer::observe(SupplierOrCustomerObserver::class);
        FiscalYear::observe(FiscalYearObserver::class);
        Transactions::observe(TransactionObserver::class);
        TransactionDetail::observe(TransactionDetailsObserver::class);
        TransactionHistory::observe(TransactionHistoryObserver::class);
        CashOrBankAccount::observe(CashAndBankAccountObserver::class);
        AccountType::observe(AccountTypesObserver::class);
        JournalEntryDetail::observe(JournalEntryDetailsObserver::class);
        Unit::observe(UnitObserver::class);
        Stock::observe(StockObserver::class);
        Category::observe(CategoryObserver::class);
        Purchase::observe(PurchaseObserver::class);
        Item::observe(ItemObserver::class);
        ReturnPurchase::observe(ReturnPurchaseObserver::class);
        SupplierPurchases::observe(SupplierPurchaseObserver::class);
        Sale::observe(SaleObserver::class);
        TrackShoppingBags::observe(TrackShoppingBagsObserver::class);
        SaleReturn::observe(SaleReturnObserver::class);
        StockHistory::observe(StockHistoryObserver::class);
        DueCollectionHistory::observe(DueCollectionHistoryObserver::class);
        Branch::observe(BranchObserver::class);
        Area::observe(AreaObserver::class);
        Bank::observe(BankObserver::class);
        ChequeEntry::observe(ChequeEntryObserver::class);
        Reconciliation::observe(ReconciliationObserver::class);
        Requisition::observe(RequisitionObserver::class);
        SalesRequisition::observe(SalesRequisitionObserver::class);
        Brand::observe(BrandObserver::class);
        DealerStock::observe(DealerStockObserver::class);
        DealerSale::observe(DealerSaleObserver::class);
        Customer::observe(CustomerObserver::class);
        LossStockReconcile::observe(LossStockObserver::class);
        StockOverflowReconcile::observe(StockOverflowObserver::class);
    }
}
