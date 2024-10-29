<?php

use Illuminate\Database\Seeder;

class NewPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array(
            array('id' => '1','name' => 'edit-roles','display_name' => 'Edit Role','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:21:26','updated_at' => '2019-09-24 12:21:26'),
            array('id' => '2','name' => 'add-role','display_name' => 'Add Role','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:21:43','updated_at' => '2019-09-24 12:21:43'),
            array('id' => '3','name' => 'roles','display_name' => 'Roles List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:22:16','updated_at' => '2019-09-24 12:22:16'),
            array('id' => '2','name' => 'delete-role','display_name' => 'Delete Role','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:21:43','updated_at' => '2019-09-24 12:21:43'),


            array('id' => '4','name' => 'create-purchase','display_name' => 'Create Purchase','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:23:19','updated_at' => '2019-09-24 12:23:19'),
            array('id' => '5','name' => 'list-purchase','display_name' => 'List Purchases','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:23:49','updated_at' => '2019-09-24 12:23:49'),
            array('id' => '6','name' => 'purchase-due-list','display_name' => 'Purchase Due List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:30:25','updated_at' => '2019-09-24 12:30:25'),
            array('id' => '7','name' => 'purchase-return-list','display_name' => 'Purchase Return List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:33:28','updated_at' => '2019-09-24 13:08:16'),
            array('id' => '8','name' => 'edit-purchase','display_name' => 'Edit Purchase','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:37:35','updated_at' => '2019-09-24 12:37:35'),
            array('id' => '9','name' => 'show-purchase','display_name' => 'Show Purchase','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:40:36','updated_at' => '2019-09-24 12:40:36'),
            array('id' => '10','name' => 'print-purchase','display_name' => 'Print Purchase','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:49:12','updated_at' => '2019-09-24 12:49:12'),
            array('id' => '11','name' => 'purchase-return-edit','display_name' => 'Purchase Return Edit','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:50:19','updated_at' => '2019-09-24 12:50:19'),
            array('id' => '12','name' => 'purchase-return-view','display_name' => 'Purchase Return view','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:51:54','updated_at' => '2019-09-24 12:51:54'),
            array('id' => '2','name' => 'delete-purchase','display_name' => 'Delete Purchase','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:21:43','updated_at' => '2019-09-24 12:21:43'),

            array('id' => '13','name' => 'list-users','display_name' => 'List User','description' => NULL,'status' => 'active','created_at' => '2019-09-24 12:59:58','updated_at' => '2019-09-24 12:59:58'),
            array('id' => '14','name' => 'set-branch-user','display_name' => 'Set Branch User','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:00:26','updated_at' => '2019-09-24 13:00:26'),
            array('id' => '15','name' => 'set-company-user','display_name' => 'Set Company User','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:00:52','updated_at' => '2019-09-24 13:00:52'),



            array('id' => '16','name' => 'create-sale','display_name' => 'Create Sale','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:01:23','updated_at' => '2019-09-24 13:01:23'),
            array('id' => '17','name' => 'list-sales','display_name' => 'List Sales','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:01:47','updated_at' => '2019-09-24 13:01:47'),
            array('id' => '18','name' => 'sales-due-list','display_name' => 'Sales Due List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:02:16','updated_at' => '2019-09-24 13:02:16'),
            array('id' => '19','name' => 'sales-return-list','display_name' => 'Sales Return List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:02:55','updated_at' => '2019-09-24 13:02:55'),
            array('id' => '23','name' => 'show-sales','display_name' => 'Show Sales','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:22:22','updated_at' => '2019-09-24 13:22:22'),
            array('id' => '24','name' => 'edit-sale','display_name' => 'Edit Sale','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:22:49','updated_at' => '2019-09-24 13:22:49'),
            array('id' => '25','name' => 'print-sale','display_name' => 'Print Sale','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:23:34','updated_at' => '2019-09-24 13:23:34'),
            array('id' => '26','name' => 'sale-return','display_name' => 'Sale Return','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:24:43','updated_at' => '2019-09-24 13:24:43'),



            array('id' => '20','name' => 'admin-dashboard','display_name' => 'Admin Dashboard','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:19:49','updated_at' => '2019-09-24 13:19:49'),
            array('id' => '21','name' => 'member-dashboard','display_name' => 'Member Dashboard','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:20:12','updated_at' => '2019-09-24 13:20:12'),
            array('id' => '22','name' => 'user-dashboard','display_name' => 'User Dashboard','description' => NULL,'status' => 'active','created_at' => '2019-09-24 13:20:32','updated_at' => '2019-09-24 13:20:32'),


            array('id' => '27','name' => 'add-supplier','display_name' => 'Add Supplier','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:05:06','updated_at' => '2019-09-24 14:05:06'),
            array('id' => '28','name' => 'list-suppliers','display_name' => 'List Suppliers','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:05:54','updated_at' => '2019-09-24 14:05:54'),
            array('id' => '29','name' => 'edit-supplier','display_name' => 'Edit Supplier','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:06:25','updated_at' => '2019-09-24 14:06:25'),


            array('id' => '30','name' => 'add-customer','display_name' => 'Add  Customer','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:38:10','updated_at' => '2019-09-24 14:38:10'),
            array('id' => '31','name' => 'list-customer','display_name' => 'List Customer','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:39:31','updated_at' => '2019-09-24 14:39:31'),
            array('id' => '32','name' => 'edit-customer','display_name' => 'Edit Customer','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:39:52','updated_at' => '2019-09-24 14:39:52'),


            array('id' => '33','name' => 'create-cash-bank-account','display_name' => 'Create Cash or Bank Account','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:42:06','updated_at' => '2019-09-24 14:42:06'),
            array('id' => '34','name' => 'list-accounts','display_name' => 'List  Accounts','description' => NULL,'status' => 'active','created_at' => '2019-09-24 14:56:40','updated_at' => '2019-09-24 14:56:40'),


            array('id' => '35','name' => 'report-list','display_name' => 'Report List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:01:01','updated_at' => '2019-09-24 15:01:01'),
            array('id' => '36','name' => 'purchase-report','display_name' => 'Purchase Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:12:15','updated_at' => '2019-09-24 15:12:15'),
            array('id' => '37','name' => 'supplier-due-report','display_name' => 'Supplier Due Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:13:06','updated_at' => '2019-09-24 15:13:06'),
            array('id' => '38','name' => 'sale-report','display_name' => 'Sale Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:13:54','updated_at' => '2019-09-24 15:13:54'),
            array('id' => '39','name' => 'customer-due-report','display_name' => 'Customer Due Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:14:38','updated_at' => '2019-09-24 15:14:38'),


            array('id' => '40','name' => 'daily-stocks-report','display_name' => 'Daily Stocks Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:15:26','updated_at' => '2019-09-24 15:15:26'),
            array('id' => '41','name' => 'stocks-report','display_name' => 'Stocks Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:16:19','updated_at' => '2019-09-24 15:16:19'),
            array('id' => '42','name' => 'balance-sheet','display_name' => 'Balance Sheet','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:17:18','updated_at' => '2019-09-24 15:17:18'),
            array('id' => '43','name' => 'supplier-due-collection-report','display_name' => 'Supplier Due Collection Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:22:01','updated_at' => '2019-09-24 15:22:01'),
            array('id' => '44','name' => 'customer-due-collection-report','display_name' => 'Customer Due Collection Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:24:14','updated_at' => '2019-09-24 15:24:14'),
            array('id' => '45','name' => 'sale-due-report','display_name' => 'Sale Due Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:27:10','updated_at' => '2019-09-24 15:27:10'),
            array('id' => '46','name' => 'purchase-due-report','display_name' => 'Purchase Due Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:27:36','updated_at' => '2019-09-24 15:27:36'),
            array('id' => '47','name' => 'transport-cost-report','display_name' => 'Transport Cost Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:28:35','updated_at' => '2019-09-24 15:28:35'),
            array('id' => '48','name' => 'unload-cost-report','display_name' => 'Unload Cost Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:28:58','updated_at' => '2019-09-24 15:28:58'),
            array('id' => '49','name' => 'product-purchase-report','display_name' => 'Product Purchase Report','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:30:20','updated_at' => '2019-09-24 15:30:20'),
            array('id' => '50','name' => 'purchase-report-by-supplier','display_name' => 'Purchase Report by Supplier','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:32:00','updated_at' => '2019-09-24 15:32:00'),



            array('id' => '51','name' => 'payment-methods','display_name' => 'Payment Methods','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:47:25','updated_at' => '2019-09-24 15:47:25'),
            array('id' => '52','name' => 'general-settings','display_name' => 'General Settings','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:47:56','updated_at' => '2019-09-24 15:47:56'),



            array('id' => '53','name' => 'add-category','display_name' => 'Add Category','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:48:14','updated_at' => '2019-09-24 15:48:14'),
            array('id' => '54','name' => 'category-list','display_name' => 'Category List','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:48:48','updated_at' => '2019-09-24 15:48:48'),
            array('id' => '55','name' => 'edit-category','display_name' => 'Edit Category','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:49:17','updated_at' => '2019-09-24 15:49:17'),
            array('id' => '56','name' => 'delete-category','display_name' => 'Remove Category','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:49:47','updated_at' => '2019-09-24 15:49:47'),


            array('id' => '57','name' => 'delete-supplier','display_name' => 'Remove Supplier','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:50:44','updated_at' => '2019-09-24 15:50:44'),
            array('id' => '58','name' => 'delete-customer','display_name' => 'Remove Customer','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:51:24','updated_at' => '2019-09-24 15:51:24'),


            array('id' => '59','name' => 'add-product','display_name' => 'Add Product','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:55:56','updated_at' => '2019-09-24 15:55:56'),
            array('id' => '60','name' => 'edit-product','display_name' => 'Edit Product','description' => NULL,'status' => 'active','created_at' => '2019-09-24 15:56:11','updated_at' => '2019-09-24 15:56:11'),
            array('id' => '61','name' => 'list-product','display_name' => 'List Products','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:00:35','updated_at' => '2019-09-24 16:00:35'),
            array('id' => '62','name' => 'barcode-print','display_name' => 'Barcode Print','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:03:02','updated_at' => '2019-09-24 16:03:02'),
            array('id' => '63','name' => 'delete-product','display_name' => 'Remove Product','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:04:46','updated_at' => '2019-09-24 16:04:46'),



            array('id' => '64','name' => 'add-unit','display_name' => 'Add Unit','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:05:43','updated_at' => '2019-09-24 16:05:43'),
            array('id' => '65','name' => 'edit-unit','display_name' => 'Edit Unit','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:06:30','updated_at' => '2019-09-24 16:06:30'),
            array('id' => '66','name' => 'delete-unit','display_name' => 'Remove Unit','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:07:01','updated_at' => '2019-09-24 16:07:01'),


            array('id' => '67','name' => 'branch-list','display_name' => 'List Branches','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:07:48','updated_at' => '2019-09-24 16:07:48'),
            array('id' => '68','name' => 'add-branch','display_name' => 'Add Branch','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:08:19','updated_at' => '2019-09-24 16:08:19'),
            array('id' => '69','name' => 'edit-branch','display_name' => 'Edit Branch','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:08:50','updated_at' => '2019-09-24 16:08:50'),
            array('id' => '70','name' => 'delete-branch','display_name' => 'Remove Branch','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:11:07','updated_at' => '2019-09-24 16:11:07'),


            array('id' => '72','name' => 'update-all-account-head-balance','display_name' => 'Update all account head balance','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:10:54','updated_at' => '2020-03-01 11:10:54'),
            array('id' => '74','name' => 'update-account-head-balance','display_name' => 'Update account head balance','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:15:00','updated_at' => '2020-03-01 11:15:00'),
            array('id' => '75','name' => 'set-account-head-balance','display_name' => 'Set account head balance','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:15:26','updated_at' => '2020-03-01 11:15:26'),
            array('id' => '76','name' => 'update_daily_cash_balance','display_name' => 'Update daily cash balance','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:16:43','updated_at' => '2020-03-01 11:16:43'),


            array('id' => '77','name' => 'general-ledgers','display_name' => 'General ledgers','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:18:24','updated_at' => '2020-03-01 11:18:24'),
            array('id' => '78','name' => 'general-ledger/list','display_name' => 'General ledger list','description' => NULL,'status' => 'active','created_at' => '2020-03-01 11:20:20','updated_at' => '2020-03-01 11:20:20'),



            array('id' => '84','name' => 'sale_delete','display_name' => 'Sale Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'purchase_delete','display_name' => 'Purchase Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'transaction_delete','display_name' => 'Transaction Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'transaction_delete','display_name' => 'Transaction Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'cash_bank_delete','display_name' => 'Cash and Bank Account Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'requisition_delete','display_name' => 'Requisition Delete','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'transaction_edit','display_name' => 'Transaction Edit','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),


            array('id' => '84','name' => 'requisition_create','display_name' => 'Requisition Create','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'requisition_edit','display_name' => 'Requisition Edit','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'requisition_purchase','display_name' => 'Requisition Edit','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),


        );

        foreach ($permissions as $key => $value){

            $data = array();
            $data['name'] = $value['name'];
            $data['display_name'] = $value['display_name'];
            $savePermission = \App\Models\Permission::create($data);
            $savePermission->save();
        }
    }
}
