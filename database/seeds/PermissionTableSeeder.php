<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array(
            array('id' => '71','name' => 'super-admin','display_name' => 'Super Admin','description' => NULL,'status' => 'active','created_at' => '2019-09-24 16:17:55','updated_at' => '2019-09-24 16:17:55'),
            array('id' => '79','name' => 'reports','display_name' => 'Reports','description' => NULL,'status' => 'active','created_at' => '2020-03-01 12:27:06','updated_at' => '2020-03-01 12:27:06'),
            array('id' => '80','name' => 'admin','display_name' => 'Admin','description' => NULL,'status' => 'active','created_at' => '2020-03-01 12:28:41','updated_at' => '2020-03-01 12:28:41'),
            array('id' => '81','name' => 'accountant','display_name' => 'Accountant','description' => NULL,'status' => 'active','created_at' => '2020-03-01 12:28:51','updated_at' => '2020-03-01 12:28:51'),
            array('id' => '82','name' => 'sales','display_name' => 'Sales','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:14:42','updated_at' => '2020-03-01 14:14:42'),
            array('id' => '83','name' => 'purchase','display_name' => 'Purchase','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:15:04','updated_at' => '2020-03-01 14:15:04'),
            array('id' => '84','name' => 'sales_report','display_name' => 'Sales report','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'purchases_report','display_name' => 'Purchases report','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'sales_requisition','display_name' => 'Sales requisition','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'purchases_requisition','display_name' => 'Purchases  requisition','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'dealer_report','display_name' => 'Dealer report','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            array('id' => '84','name' => 'delete_access','display_name' => 'Delete Access','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
            // array('id' => '84','name' => 'purchases_report','display_name' => 'Sales requisition','description' => NULL,'status' => 'active','created_at' => '2020-03-01 14:25:52','updated_at' => '2020-03-01 14:25:52'),
        );

        foreach ($permissions as $key => $value){

            $data = array();
            $data['name'] = $value['name'];
            $data['display_name'] = $value['display_name'];
            $savePermission = \App\Models\Permission::create($data);

            if($value['name'] == "super-admin" )
            {

                $role =  \App\Models\Role::where('name', 'super-admin')->first();
                $role->attachPermission($savePermission);
                $role =  \App\Models\Role::where('name', 'developer')->first();
                $role->attachPermission($savePermission);

            }else if($value['name'] == "delete_access") {

                $role =  \App\Models\Role::where('name', 'super-admin')->first();
                $role->attachPermission($savePermission);
                $role =  \App\Models\Role::where('name', 'admin')->first();
                $role->attachPermission($savePermission);

            }else if($value['name'] == "admin") {

                $role =  \App\Models\Role::where('name', 'admin')->first();
                $role->attachPermission($savePermission);

            }else {

                $role =  \App\Models\Role::where('name', 'accountant')->first();
                $role->attachPermission($savePermission);
                $role =  \App\Models\Role::where('name', 'user')->first();
                $role->attachPermission($savePermission);
            }


        }

        $routeCollection = Route::getRoutes();

        DB::statement("DELETE  FROM `permissions` WHERE `id`> 12; ");
        foreach ($routeCollection as  $value) {

            $route = $value->uri();
            $name = $value->getName();
            $action = $value->getActionName();
            $checkMain = explode('.',$name);

            if(
                ($checkMain[0] == "member" || $checkMain[0] == "admin")
                && $checkMain[1] != 'signin' && $checkMain[1] != 'support_pin_login'
                &&  $checkMain[1] != 'memberships' && $checkMain[1] != 'packages'
                &&  $checkMain[1] != 'permissions' && $checkMain[1] != 'payment_methods'
                &&  $checkMain[1] != 'transaction_categories' && $checkMain[1] != 'members'
            )
            {

                $module = $checkMain[1];
                $action = isset($checkMain[2]) ? $checkMain[2] : "";

                if($action == "index"){
                    $action = "List/All";
                }elseif($action == "destroy"){
                    $action = "Delete";
                }
                $action = ucfirst(normal_writing_format($action));

                $inputs = [];
                $inputs['group_name'] = $module;
                $inputs['name'] = $name;
                $inputs['display_name'] = $action != "" ? $action : ucfirst(normal_writing_format($module));

//                if($module == "stock_reconcile")
//                    dd($inputs);


                if( $inputs['display_name'] == "Update" || $inputs['display_name'] == "Store" || $value->getPrefix() == "/employee")
                {
                }else{
                    $permission = Permission::firstOrCreate($inputs);

                    $role =  \App\Models\Role::where('name', 'admin')->first();
                    $role->attachPermission($permission);
                }

            }
        }

        DB::statement("DELETE FROM `permissions` WHERE `name` LIKE '%save_message_from_admin%';");
        DB::statement("DELETE FROM `permissions` WHERE `name` LIKE '%send_mail_to_supplires%';");
        DB::statement("DELETE FROM `permissions` WHERE `name` LIKE '%search%';");
        DB::statement("DELETE FROM `permissions` WHERE `group_name` LIKE '%restaurant%';");
        DB::statement("DELETE  FROM `permissions` WHERE `name` LIKE '%show_message_from_admin%';");
        DB::statement("DELETE  FROM `permissions` WHERE `name` LIKE '%showDesignationByDeptId%';");
        DB::statement("DELETE  FROM `permissions` WHERE `name` LIKE '%member.account_type.save%';");
        DB::statement("DELETE  FROM `permissions` WHERE `name` LIKE '%member.customer.save%';");
        DB::statement(" DELETE  FROM `permissions` WHERE `display_name` = 'Update';");
        DB::statement("DELETE  FROM `permissions` WHERE `display_name` = 'Store'; ");

        DB::statement("UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.stock_reconcile%';");
        DB::statement("UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.damage_stock_update%';");
        DB::statement("UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.daily_stock.delete%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.damage_stock_update%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.get_update_stock_report%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.update_stock_reconcile%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.update_stock_overflow_reconcile%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.update_stock_report%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.yearly_stock_reconcile%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.warehouse_update_stock_report%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.check_update_stock_report%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.stock_report_update%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update'  WHERE `name` LIKE '%admin.backup%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition'  WHERE `name` LIKE '%member.approve_list%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition'  WHERE `name` LIKE '%member.requisition_completed%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition'  WHERE `name` LIKE '%member.requisition_approved%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition'  WHERE `name` LIKE '%member.requisition_expenses.approved%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition'  WHERE `name` LIKE '%member.requisition_not_approve_list%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'staff_support' WHERE `name` LIKE '%admin.change_status_support_message%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'procurements' WHERE `name` LIKE '%member.procurements_budget_approve%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'procurements' WHERE `name` LIKE '%member.procurements_budget_not_approve%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'procurements' WHERE `name` LIKE '%member.budget_print_by_id%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'procurements' WHERE `name` LIKE '%member.budget_print%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'attendances' WHERE `name` LIKE '%member.checkinout-attendances%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'attendances' WHERE `name` LIKE '%member.master-attendances%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'attendances' WHERE `name` LIKE '%member.process-attendances%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'attendances' WHERE `name` LIKE '%member.summary-attendances%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'couriers' WHERE `name` LIKE '%member.courier_settings.store%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'sharer' WHERE `name` LIKE '%member.customer_due_payment%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'label' WHERE `name` LIKE '%member.destroyLabel%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'label' WHERE `name` LIKE '%member.updateLabel%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'task' WHERE `name` LIKE '%member.assign_employee_to_task%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'task' WHERE `name` LIKE '%member.show_task%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'task' WHERE `name` LIKE '%member.change_task_status%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'task' WHERE `name` LIKE '%member.assign_employee_to_task%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'lead' WHERE `name` LIKE '%member.editLeadStatus%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'lead' WHERE `name` LIKE '%member.showLead%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'sales' WHERE `name` LIKE '%member.employee_salary_paid%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'sales' WHERE `name` LIKE '%member.sale_commission_paid%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'project' WHERE `name` LIKE '%member.singleProject%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'project' WHERE `name` LIKE '%member.set_deadline%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'project' WHERE `name` LIKE '%member.project_wise_task%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'purchases_by_requisition' WHERE `name` LIKE '%member.purchase_by_requisition.print_purchases%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition' WHERE `name` LIKE '%member.requisition_quotation.print%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'requisition' WHERE `name` LIKE '%member.purchase-requisition.index%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'product_log_books' WHERE `name` LIKE '%member.requisition_quotation.print%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'supplier_quotation' WHERE `name` LIKE '%member.supplier_quotation_po%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'product_log_books' WHERE `name` LIKE '%member.print_log_books%';");
        DB::statement(" UPDATE `permissions` SET `group_name` = 'admin_check_update' WHERE `group_name` = 'ledger_book';");
        DB::statement(" UPDATE `permissions` SET `display_name` = 'Supplier create', `name` = 'member.supplier_create'  WHERE `name` = 'member.sharer.create';");
        DB::statement(" INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.customer_create', 'Customer Create', NULL, 'active', '2024-04-08 12:00:42', '2024-04-08 12:00:42', 'sharer');");


    }
}
