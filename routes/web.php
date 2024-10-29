<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



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

Route::get('/home',  [FrontendController::class, 'index']);
Route::get('/category/{id}',  [FrontendController::class, 'category'])->name('frontend.category');
Route::get('/product/{id}',  [FrontendController::class, 'product'])->name('frontend.product');



Route::group(['middleware' => 'language'], function () {

    Route::get('database-process/{id}', 'DatabaseController@index');
    Route::resource('system-users', 'SystemUserController');

    Auth::routes();
    Auth::routes(['verify' => true]);

    // Admin Routes
    Route::get('admin', function () {
        return redirect()->route('admin.signin');
    });


    Route::get('/login-for-support', ['as' => 'admin.support_pin_login', 'uses' => 'Auth\SupportLoginController@showLoginForm']);
    Route::post('/login-for-support', ['as' => 'support_pin_login', 'uses' => 'Auth\SupportLoginController@login']);


    //    Route::middleware('tenant')->group(function() {

    Route::get('/login', ['as' => 'admin.signin', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::get('/signin', ['as' => 'employee.signin', 'uses' => 'Auth\LoginController@showEmployeeLoginForm']);
    Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);

    //    });

    Route::get('/verify/registration/{id}', ['as' => 'verify.registration', 'uses' => 'Auth\VerificationController@verifyUser']);

    Route::post('set_password', ['as' => 'password.set', 'uses' => 'Auth\SetPasswordController@set_password']);
    Route::get('user/change-password', ['as' => 'change_password', 'uses' => 'Auth\ChangePasswordController@showChangePasswordForm']);
    Route::post('user/change-password', ['as' => 'auth.change_password', 'uses' => 'Auth\ChangePasswordController@changePassword']);


    Route::post('check-item-serial', ['as' => 'search.check_item_serial', 'uses' => 'SearchController@check_item_serial']);
    Route::post('brand-items', ['as' => 'search.brand_items', 'uses' => 'SearchController@search_brand_items']);
    Route::post('item-details', ['as' => 'search.item_details', 'uses' => 'SearchController@item_details']);
    Route::post('warehouse-item-details', ['as' => 'warehouse.item_details', 'uses' => 'SearchController@warehouse_item_details']);
    Route::post('search-item', ['as' => 'search.item', 'uses' => 'SearchController@search_item']);
    Route::post('search-variant', ['as' => 'search.variant', 'uses' => 'SearchController@search_variant']);
    Route::post('generate-variant-product', ['as' => 'generate.variant_product', 'uses' => 'SearchController@generate_variant_product']);

    Route::post('search-membership', ['as' => 'search.membership', 'uses' => 'SearchController@search_membership']);
    Route::post('search-customer-phone', ['as' => 'search.customer_phone', 'uses' => 'SearchController@search_customer_phone']);
    Route::post('sale-item-details', ['as' => 'search.sale_item_details', 'uses' => 'SearchController@sale_item_details']);
    Route::post('sale-bags', ['as' => 'search.sale_bags', 'uses' => 'SearchController@sale_bags']);
    Route::post('supplier-info', ['as' => 'search.supplier_info', 'uses' => 'SearchController@supplier_info']);
    Route::post('customer-info', ['as' => 'search.customer_info', 'uses' => 'SearchController@customer_info']);
    Route::post('select-bank-branch', ['as' => 'search.select_bank_branch', 'uses' => 'SearchController@select_bank_branch']);
    Route::post('select-region', ['as' => 'search.select_region', 'uses' => 'SearchController@select_region']);
    Route::post('select-district', ['as' => 'search.select_district', 'uses' => 'SearchController@select_district']);
    Route::post('select-upazilla', ['as' => 'search.select_upazilla', 'uses' => 'SearchController@select_upazilla']);
    Route::post('select-union', ['as' => 'search.select_union', 'uses' => 'SearchController@select_union']);
    Route::post('select-area', ['as' => 'search.select_area', 'uses' => 'SearchController@select_area']);
    Route::post('select-region-district', ['as' => 'search.select_region_district', 'uses' => 'SearchController@select_region_district']);
    Route::post('select-district-thana', ['as' => 'search.select_district_thana', 'uses' => 'SearchController@select_district_thana']);

    Route::post('select-quote-attentions', ['as' => 'search.quote_attentions', 'uses' => 'SearchController@search_quote_attention']);
    Route::post('order_search', ['as' => 'order_search', 'uses' => 'SearchController@order_search']);


    Route::group([
        'middleware' => ['login_session']
    ], function () {

        Route::group([
            'middleware' => ['auth']
        ], function () {

            Route::get('menu-change/{id}', ['as' => 'menu_change', 'uses' => 'HomeController@menu_change']);

            Route::get('left-sidebar-toggle', ['as' => 'left_sidebar_toggle', 'uses' => 'HomeController@left_sidebar_toggle']);
            Route::get('user/profile', ['as' => 'auth.profile', 'uses' => 'Auth\UserProfileController@showProfile']);
            Route::post('user/update-profile', ['as' => 'auth.update_profile', 'uses' => 'Auth\UserProfileController@updateProfile']);

            Route::get('user-profile', ['as' => 'users.user_profile', 'uses' => 'Auth\UserProfileController@user_profile']);
        });


        Route::group([
            'prefix' => 'admin',
            'namespace' => 'Admin',
            'as' => 'admin.'
        ], function () {

            Route::group([
                'middleware' => ['auth', 'role:accountant|user|admin|super-admin'],
            ], function () {

                /*
                *  Start AccountTypeController
                *  All Ledgers Manage by This Controller
                */

                Route::resource('account_types', 'AccountTypeController')->except(['show']);
                Route::delete('account-type-force-delete', ['as' => 'account_types.force_delete', 'uses' => 'AccountTypeController@forcedelete']);
                Route::get('account-group', ['as' => 'account_types.group', 'uses' => 'AccountTypeController@group']);
                Route::get('account-heads', ['as' => 'account_types.heads', 'uses' => 'AccountTypeController@heads']);
                Route::get('account-sub-heads', ['as' => 'account_types.sub_heads', 'uses' => 'AccountTypeController@sub_heads']);


                /*
                 *  Import Controller
                 *  For Product Import: ProductImportController
                 *  For Customer/Supplier Import: SharerImportController
                 */

                Route::resource('product_import', 'ProductImportController')->only(['create', 'store']);
                Route::get('product-import-sample', ['as' => 'product_import.sample', 'uses' => 'ProductImportController@productImportSample']);
                Route::resource('sharer_import', 'SharerImportController')->only(['create', 'store']);
                Route::get('customer-import-sample', ['as' => 'sharer_import.sample_customer', 'uses' => 'SharerImportController@customerImportSample']);
                Route::get('supplier-import-sample', ['as' => 'sharer_import.sample', 'uses' => 'SharerImportController@supplierImportSample']);
                Route::resource('employee_import', 'EmployeeImportController')->only(['create', 'store']);
                Route::get('employee-import-sample', ['as' => 'employee_import.sample', 'uses' => 'EmployeeImportController@sample']);


                Route::resource('gl_account_class', 'GLAccountClassController')->except(['show']);
                Route::resource('units', 'UnitController')->except(['show']);
            });

            Route::group([
                'middleware' => ['auth', 'role:developer|admin|super-admin'],
            ], function () {


                Route::get('backup', ['as' => 'backup', 'uses' => 'BackupController@backupDB']);

                Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
                Route::get('employee-show-join-month', ['as' => 'employee.show-join-month', 'uses' => 'DashboardController@month_based_joined']);


                Route::resource('memberships', 'MembershipController')->except(['show', 'destroy']);
                Route::resource('packages', 'PackagesController')->except(['show', 'destroy']);
                Route::resource('roles', 'RoleController')->except(['show']);

                Route::get('role-permission', ['as' => 'role-permission', 'uses' => 'RoleController@role_permission']);

                Route::resource('permissions', 'PermissionController')->except(['show', 'destroy']);


                Route::resource('payment_methods', 'PaymentMethodController')->only(['index']);
                //                Route::resource('transaction_categories', 'TransactionCategoryController');
                Route::get('expense-transaction-categories', ['as' => 'transaction_categories.expense', 'uses' => 'TransactionCategoryController@expense_category_list']);
                Route::get('income-transaction-categories', ['as' => 'transaction_categories.income', 'uses' => 'TransactionCategoryController@income_category_list']);


                Route::get('stock-reconcile/{id}', ['as' => 'stock_reconcile', 'uses' => 'StockController@stock_reconcile']);
                Route::post('update-damage-stock', ['as' => 'damage_stock_update', 'uses' => 'StockController@damage_stock_update']);
                Route::get('yearly-stock-reconcile', ['as' => 'yearly_stock_reconcile', 'uses' => 'StockController@yearly_stock_reconcile']);
                Route::post('update-stock-reconcile', ['as' => 'update_stock_reconcile', 'uses' => 'StockController@update_stock_reconcile']);
                Route::post('update-stock-overflow-reconcile', ['as' => 'update_stock_overflow_reconcile', 'uses' => 'StockController@update_stock_overflow_reconcile']);

                Route::resource('members', 'MemberController')->except(['show', 'destroy']);
                Route::resource('users', 'UserController')->only(['create', 'index', 'destroy']);
                Route::get('users-support-pin', ['as' => 'users.support_pin', 'uses' => 'UserController@support_pin_generate']);
                Route::resource('staff_support', 'StaffSupportController');
                Route::get('change-status-support-message', ['as' => 'change_status_support_message', 'uses' => 'StaffSupportController@change_status_support_message']);
            });
        });


        Route::group([
            'prefix' => 'employee',
            'namespace' => 'Member',
            'as' => 'member.'
        ], function () {


            Route::group([
                'middleware' => ['auth', 'role:user|project_manager|developer|admin|super-admin'],
            ], function () {

                Route::get(
                    'user-task',
                    ['as' => 'users.user_task', 'uses' => 'TaskController@user_task']
                );
                Route::get(
                    'edit-task-status',
                    ['as' => 'users.editTaskStatus', 'uses' => 'TaskController@editTaskStatus']
                );
                Route::get(
                    'user-project',
                    ['as' => 'users.user_project', 'uses' => 'ProjectController@index']
                );
                Route::get(
                    'project-wise-task/{id}',
                    ['as' => 'users.project_wise_task', 'uses' => 'ProjectController@project_wise_task']
                );
                Route::get('index', ['as' => 'users.project.index', 'uses' => 'ProjectController@index']);
                Route::get('show', ['as' => 'project.show', 'uses' => 'ProjectController@show']);
                Route::get(
                    'user_kanban/{id}/show',
                    ['as' => 'users.kanban_list', 'uses' => 'TaskController@UserKanbanList']
                );
                Route::get(
                    'change-task-status',
                    ['as' => 'users.change_task_status', 'uses' => 'TaskController@change_task_status']
                );


                Route::get('show_task', ['as' => 'show_task', 'uses' => 'TaskController@show_task']);
                Route::post('assign_employee_to_task', ['as' => 'assign_employee_to_task', 'uses' => 'TaskController@assignEmployeeToTask']);
                Route::get('show-task', ['as' => 'users.show_task', 'uses' => 'TaskController@show_task']);
                Route::get('task-details', ['as' => 'users.task_details', 'uses' => 'TaskController@show_task']);
                Route::get('project/{id}/task', ['as' => 'users.show_task', 'uses' => 'TaskController@create']);


                Route::get(
                    'project/{id}/show',
                    ['as' => 'users.project.deatils', 'uses' => 'ProjectController@show']
                );
                Route::get(
                    'project_wise_task/{id}',
                    ['as' => 'employee.project_wise_task', 'uses' => 'ProjectController@project_wise_task']
                );
                Route::post(
                    'add/task/comment',
                    ['as' => 'users.add_task_comment', 'uses' => 'TaskController@addTaskComment']
                );
                Route::get(
                    'delete/task/comment',
                    ['as' => 'users.task_comment_delete', 'uses' => 'TaskController@DeleteTaskComment']
                );

                Route::get(
                    'singleProject',
                    ['as' => 'employee.singleProject', 'uses' => 'ProjectController@singleProject']
                );


                //                Route::get('/{id}/edit', ['as' => 'employee_edit', 'uses' => 'EmployeeController@employeeEdit']);
                //                 Route::put('{id}/update', ['as' => 'employee_update', 'uses' => 'EmployeeController@employeeUpdate']);
                Route::get('show/designation/ByDeptId', [
                    'as' => 'employee.showDesignationByDeptId',
                    'uses' => 'EmployeeController@showDesignationByDeptId'
                ]);



                Route::get('show/state/byCountryId', [
                    'as' => 'employee.showStateByCountryId',
                    'uses' => 'EmployeeController@showStateByCountryId'
                ]);

                Route::get('show/city/byStateId', [
                    'as' => 'employee.showCityByStateId',
                    'uses' => 'EmployeeController@showCityByStateId'
                ]);

                Route::get('show/area/byCityId', [
                    'as' => 'employee.showAreaByCityId',
                    'uses' => 'EmployeeController@showAreaByCityId'
                ]);

                Route::resource('personal_info', 'EmployeePersonalInfoController')->only(['edit', 'update']);

                Route::get('employee-salary', [
                    'as' => 'salary_management.employee-data',
                    'uses' => 'SalaryManagementController@employee_salary'
                ]);
            });
        });

        Route::group([
            'prefix' => 'member',
            'namespace' => 'Member',
            'as' => 'member.'
        ], function () {


            Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
            Route::resource('requisition_expenses', 'RequisitionExpenseController');

            Route::group([
                'middleware' => ['auth', 'role:master-member|developer|admin|super-admin|accountant'],
            ], function () {

                Route::resource('settings', 'SettingController')->only(['store']);
                Route::get('company-fiscal-year', [
                    'as' => 'settings.company_fiscal_year',
                    'uses' => 'SettingController@company_fiscal_year'
                ]);
                Route::post('set-company-fiscal-year', [
                    'as' => 'settings.set_company_fiscal_year',
                    'uses' => 'SettingController@set_company_fiscal_year'
                ]);
                Route::get('general-settings', [
                    'as' => 'settings.general_settings',
                    'uses' => 'SettingController@general_settings'
                ]);
                Route::post('set-print-page-setup', [
                    'as' => 'settings.set_print_page_setup',
                    'uses' => 'SettingController@set_print_page_setup'
                ]);
                Route::post('set-cash-setup', [
                    'as' => 'settings.set_cash_setup',
                    'uses' => 'SettingController@set_cash_setup'
                ]);
                Route::resource('users', 'UserController');
                Route::get('set-users-company', [
                    'as' => 'users.set_users_company',
                    'uses' => 'UserController@set_users_company'
                ]);

                Route::post('set-users-company', [
                    'as' => 'users.save_users_company',
                    'uses' => 'UserController@save_users_company'
                ]);
                Route::get('users_change_password/{id}', [
                    'as' => 'users.change_password',
                    'uses' => 'UserController@change_password'
                ]);

                Route::get('project_wise_task/{id}', ['as' => 'project_wise_task', 'uses' => 'ProjectController@project_wise_task']);
                Route::get('set_deadline', ['as' => 'set_deadline', 'uses' => 'ProjectController@set_deadline']);
                Route::get('change_task_status', [
                    'as' => 'change_task_status',
                    'uses' => 'TaskController@change_task_status'
                ]);
                Route::post('update_change_password/{id}', [
                    'as' => 'users.update_change_password',
                    'uses' => 'UserController@update_change_password'
                ]);
                Route::post('save-message-from-admin', [
                    'as' => 'save_message_from_admin',
                    'uses' => 'ChatController@save_message_from_admin'
                ]);
                Route::get('show-message-from-admin', [
                    'as' => 'show_message_from_admin',
                    'uses' => 'ChatController@show_message_from_admin'
                ]);
                Route::put('destroy-label', ['as' => 'destroyLabel', 'uses' => 'LabelController@destroyLabel']);
                Route::get('update-label', ['as' => 'updateLabel', 'uses' => 'LabelController@updateLabel']);
                Route::get('edit-lead-status', [
                    'as' => 'editLeadStatus',
                    'uses' => 'LeadController@editLeadStatus'
                ]);
                Route::get('show_lead', [
                    'as' => 'showLead',
                    'uses' => 'LeadController@showLead'
                ]);
                Route::resource('fiscal_year', 'FiscalYearController');

                Route::get('singleProject', [
                    'as' => 'singleProject',
                    'uses' => 'ProjectController@singleProject'
                ]);

                Route::get('showDesignationByDeptId', [
                    'as' => 'showDesignationByDeptId',
                    'uses' => 'ProjectController@showDesignationByDeptId'
                ]);


                Route::resource('categories', 'CategoryController')->except(['show']);
                Route::resource('items', 'ItemController')->except(['show']);
                Route::resource('variant-items', 'VariantItemController');
                Route::resource('brand', 'BrandController')->except(['show']);
                Route::resource('variants', 'VariantController');

                // Repair System
                Route::resource('services', 'ServiceController');
                Route::resource('defect_types', 'DefectTypeController');
                Route::resource('repair_orders', 'RepairOrderController');

                Route::resource('procurements', 'ProcurementController');
                Route::post('procurements/budget/approve', 'ProcurementController@approve')
                    ->name('procurements_budget_approve');
                Route::post('procurements/budget/not-approve', 'ProcurementController@not_approve')
                    ->name('procurements_budget_not_approve');
                Route::get('procurements/budget/approve/list', 'ProcurementController@approve_list')
                    ->name('procurements.budget_approve_list');
                Route::get('procurements/budget/not-approve/list', 'ProcurementController@not_approve_list')
                    ->name('procurements.budget_not_approve_list');


                // Location Will changed by Country Wise
                Route::resource('states', 'DivisionController', ['names' => 'divisions'])->except(['show']);

                // State
                Route::resource('regions', 'RegionController')->except(['show']);
                Route::resource('cities', 'DistrictController', ['names' => 'districts'])->except(['show']);

                // City
                Route::resource('thanas', 'ThanaController')->except(['show']);
                Route::resource('areas', 'AreaController', ['names' => 'area'])->except(['show']);

                // Area
                //                Route::resource('branch', 'BranchController')->except(['show']);

                Route::resource('project_expense_types', 'ProjectExpenseTypeController');
                Route::resource('project_expenses', 'ProjectExpenseController');
                Route::post('requisition_expenses/approved', 'RequisitionExpenseController@approved')
                    ->name('requisition_expenses.approved');



                Route::resource('designation', 'DesignationController')->except(["show", 'destroy']);
                Route::resource('employee', 'EmployeeController');
                Route::resource('department', 'DepartmentController')->except(["show"]);
                Route::resource('holiday', 'HolidayController')->except(["show", 'destroy']);
                Route::resource('shift', 'ShiftController')->except(["show", 'destroy']);


                Route::get('hr/employee-visa-expires', 'HrController@employee_visa_expires')
                    ->name('hr.employee-visa-expires');
                Route::get('hr/employee-passport-expires', 'HrController@employee_passport_expires')
                    ->name('hr.employee-passport-expires');
                Route::get('hr/employee-visa-expires', 'HrController@employee_visa_expires')
                    ->name('hr.employee-visa-expires');
                Route::get('hr/employee-on-leaves', 'HrController@employee_on_leaves')
                    ->name('hr.employee-on-leaves');
                Route::get('hr/employee-next-attends', 'HrController@employee_next_attends')
                    ->name('hr.employee-next-attends');


                Route::resource('attendances', 'AttendanceController')
                    ->except(['index', 'edit', 'update', 'destroy']);
                Route::get('process-attendances', [
                    'as' => 'process-attendances',
                    'uses' => 'AttendanceController@process_attendance'
                ]);
                Route::get('attendances-master', [
                    'as' => 'master-attendances',
                    'uses' => 'AttendanceController@attendance_master'
                ]);
                Route::get('attendances-checkinout', [
                    'as' => 'checkinout-attendances',
                    'uses' => 'AttendanceController@attendance_checkinout'
                ]);
                Route::get('attendances-summary', [
                    'as' => 'summary-attendances',
                    'uses' => 'AttendanceController@attendance_summary'
                ]);
                Route::post('store-process-attendances', [
                    'as' => 'store.process-attendances',
                    'uses' => 'AttendanceController@store_process_attendance'
                ]);


                //              Route::resource('employee_leaves', 'EmployeeLeaveController');
                Route::resource('leaves', 'LeaveController')->except(['show']);
                Route::resource('salary_management', 'SalaryManagementController')->only([
                    'index',
                    'create',
                    'show'
                ]);
                Route::post('salary-emp-paid-status', [
                    'as' => 'salary_management.emp_paid_status',
                    'uses' => 'SalaryManagementController@emp_paid_status'
                ]);
                Route::get('salary-update', [
                    'as' => 'salary_management.salary-update',
                    'uses' => 'SalaryManagementController@create'
                ]);
                Route::post('emp-salary-update', [
                    'as' => 'salary_management.emp_update_salary',
                    'uses' => 'SalaryManagementController@emp_update_salary'
                ]);


                Route::resource('project_category', 'ProjectCategoryController');
                Route::resource('lead_category', 'LeadCategoryController');
                Route::resource('project', 'ProjectController');
                Route::resource('client', 'ClientController');
                Route::resource('client_company', 'ClientCompanyController');

                Route::resource('label', 'LabelController')->except(['show']);
                Route::resource('lead', 'LeadController');
                Route::resource('hospital_service', 'HospitalServiceController');
                Route::resource('marketing_officer', 'MarketingOfficerController');
                Route::resource('marketing_officer_commissions', 'MarketingOfficerCommissionController');
                Route::resource('doctors', 'DoctorController');
                Route::resource('birth_certificate', 'BirthCertificateController');
                Route::resource('death_certificate', 'DeathCertificateController');
                Route::resource('vehicle_info', 'VehicleInfoController');
                Route::resource('live_consultation', 'LiveConsultationController');
                Route::resource('vehicle_schedule', 'VehicleScheduleContoller');
                Route::resource('vehicle_detail', 'VehicleDetailController');
                Route::get('/reserve_vehicle', 'VehicleDetailController@reserve')->name('reserve_vehicle');
                Route::put('/booking_vehicle', 'VehicleDetailController@reserveUpdate')->name('booking_vehicle');
                Route::get('/outdoor_registration_id/{id}', 'VehicleDetailController@outdoorRegistrationId');
                Route::get('/ipd_patient_info_registration_id/{id}', 'VehicleDetailController@ipdPatientInfRegistrationId');
                Route::get('/vehicle_schedule_id/{id}', 'VehicleDetailController@vehicleScheduleId');
                Route::get('/vehicle_schedule_single_id/{id}', 'VehicleDetailController@vehicleScheduleSingleId');
                Route::post('/date_by_reserve_booking/', 'VehicleDetailController@dateReserveBookingSearch');
                Route::post('/reserve_booking_report/', 'VehicleDetailController@dateReserveBookingReport');
                Route::post('/download_reserve_booking/', 'VehicleDetailController@downloadReserveBooking');
                Route::post('/pagination_fatch', 'VehicleInfoController@fatch')->name('pagination.fetch');
                Route::get('/sorting_fatch/{id}', 'VehicleInfoController@sorting')->name('sorting.fetch');

                Route::resource('driver', 'DriverController');
                Route::resource('cabin_class', 'CabinClassController');
                Route::resource('doctor_comission', 'DoctorComissionController');
                Route::get('check/doctor/comission', 'DoctorComissionController@checkDoctorComission')->name('check.doctor.comission');
                // Route::resource('cabin_sub_class', 'CabinClassController');
                // Route::resource('room', 'CabinClassController');
                Route::get('cabin_summary', 'CabinClassController@cabinsummary')->name('cabin_class.summary');
                Route::post('cabin_update', 'CabinClassController@cabinUpdate')->name('cabin_class.update');
                Route::post('updateCabinSubClass', 'CabinClassController@updateCabinSubClass')->name('updateCabinSubClass');
                Route::post('updateRoom', 'CabinClassController@updateRoom')->name('updateRoom');
                Route::post('getCabinClass', 'CabinClassController@getCabinClass')->name('getCabinClass');
                Route::post('getCabin', 'CabinClassController@getCabin')->name('getCabin');
                Route::resource('patient_registration', 'PatientRegistrationController');
                Route::post('ipd_patient_search', 'PatientRegistrationController@ipd_patient_search')->name('ipd_patient_search');
                Route::get('add_service/{id}', 'PatientRegistrationController@add_service')->name('add_service');
                Route::get('service_history', 'PatientRegistrationController@service_history')->name('service_history');
                Route::get('service_order_show/{id}', 'PatientRegistrationController@service_order_show')->name('service_order_show');
                Route::post('service_search', 'PatientRegistrationController@service_search')->name('service_search');
                Route::post('service_store', 'PatientRegistrationController@storeService')->name('service_store');
                Route::post('qtyChange', 'PatientRegistrationController@qtyChange')->name('qtyChange');
                Route::post('removeItem', 'PatientRegistrationController@removeItem')->name('removeItem');
                Route::post('addItem', 'PatientRegistrationController@addItem')->name('addItem');
                // Route::get('ipd_reg_form/{id}', 'PatientRegistrationController@ipd_reg_form')->name('ipd_reg_form');
                Route::post('calculateAge', 'PatientRegistrationController@calculateAge')->name('calculateAge');
                Route::post('calculateDate', 'PatientRegistrationController@calculateDate')->name('calculateDate');
                Route::get('patient_form_print/{id}', 'PatientRegistrationController@patient_form_print')->name('patient_form_print');
                Route::resource('specimen', 'SpecimenController');
                Route::resource('doctor_schedule', 'DoctorScheduleController');
                Route::resource('test_group', 'TestGroupController');

                Route::resource('pathology_final_reports', 'PathologyFinalReportController');
                Route::resource('pathology_reports', 'PathologyReportController');

                Route::get('create_pathology_report/{id}', 'PathologyReportController@createPathologyReport')
                    ->name('create.pathology_report');

                Route::get('fetch_pathology_list', 'PathologyReportController@fetchPathologyList')
                    ->name('fetch.pathology_list');

                Route::post('multiple-test-print', 'PathologyReportController@multipleTestReportPrint')
                    ->name('multiple_test_print');



                Route::resource('sub_test_group', 'SubTestGroupController');

                Route::post('sub/test/print', 'SubTestGroupController@subTestPrint')
                    ->name('sub_test_print');

                Route::get('fetch_sub_tes_group', 'SubTestGroupController@fetchSubTestGroup')
                    ->name('fetch.sub_tes_group');

                Route::get('doctors_fetch', 'DoctorController@fetchDoctor')
                    ->name('doctors.fetch');

                Route::get('fetch_sub_test', 'TestGroupController@fetchSubTest')
                    ->name('fetch.subtest');

                Route::get('fetch_sub_test_by_id', 'TestGroupController@fetchSubTestByTestId')
                    ->name('fetch.subtest.byId');

                Route::get('subtest_by_specimen', 'TestGroupController@fetchTestBySpecimen')
                    ->name('fetch.test_by_specimen');

                Route::get('doctor-schedule/{schedule}/day/{id}/edit', 'DoctorScheduleController@doctorScheduleDayEdit')
                    ->name('doctor_schedule_day.edit');

                Route::resource('appoinments', 'AppoinmentController');
                Route::get('schedule_fetch', 'AppoinmentController@fetchSchedule')
                    ->name('schedule.fetch');


                // Outdoor Route Start
                Route::resource('out_door_registration', 'OutDoorRegistrationController');

                Route::get('fetch_patient', 'OutDoorRegistrationController@fetchPatient')
                    ->name('fetch.patient');

                Route::get('opd_all_patient_list', 'OutDoorRegistrationController@opdAllPatientList')
                    ->name('opd.all.patient.list');

                Route::get('opd_all_patient_list', 'OutDoorRegistrationController@opdAllPatientList')
                    ->name('opd.all.patient.list');

                Route::get('opd_paid_patient_list', 'OutDoorRegistrationController@opdPaidPatientList')
                    ->name('opd.paid.patient.list');

                Route::get('opd_due_patient_list', 'OutDoorRegistrationController@opdDuePatientList')
                    ->name('opd.due.patient.list');

                Route::get('opd_paid_patient_print/{id}', 'OutDoorRegistrationController@opdPaidPatientPrint')
                    ->name('opd.paid.patient.print');

                Route::get('specimen_wise_collection_list', 'OutDoorRegistrationController@specimenWiseCollection')
                    ->name('specimen_wise.collection.list');

                Route::get('group_wise_collection', 'OutDoorRegistrationController@groupWiseCollection')
                    ->name('group_wise.collection.list');

                Route::post('group_wise_collection_search', 'OutDoorRegistrationController@groupWiseCollectionSearch')
                    ->name('group_wise.collection.search');

                Route::get('sub_group_wise_collection', 'OutDoorRegistrationController@subGroupWiseCollection')
                    ->name('sub_group_wise.collection.list');

                Route::post('sub_group_wise_collection_search', 'OutDoorRegistrationController@subGroupWiseCollectionSearch')
                    ->name('sub_group_wise.collection.search');

                Route::get('specimen_wise_collection', 'OutDoorRegistrationController@specimenCollection')
                    ->name('specimen_wise.collection.list');

                Route::post('specimen_wise_collection_search', 'OutDoorRegistrationController@specimenWiseCollectionSearch')
                    ->name('specimen_wise.collection.search');

                Route::get('opd_discount_summary', 'OutDoorRegistrationController@opdDiscountSummary')
                    ->name('opd.discount.summary.list');

                Route::post('opd_discount_summary_search', 'OutDoorRegistrationController@opdDiscountSummarySearch')
                    ->name('opd.discount.summary.search');

                Route::get('opd_individual_billing_details/{id}', 'OutDoorRegistrationController@opdnIdividualBillingDetails')
                    ->name('opd.individual.billing.details');

                Route::get('opd_balance_sheet', 'OutDoorRegistrationController@opdBalanceSheet')
                    ->name('opd.balance_sheet.list');

                Route::post('opd_balance_sheet_search', 'OutDoorRegistrationController@opdBalanceSheetSearch')
                    ->name('opd.balance_sheet.search');

                Route::get('opd/due/list', 'OutDoorRegistrationController@otdDueList')
                    ->name('opd.due.list');

                Route::get('opd/due/update/{id}', 'OutDoorRegistrationController@otdDueCreate')
                    ->name('opd.due.update');

                Route::put('opd/update/{id}', 'OutDoorRegistrationController@opdUpdate')
                    ->name('opd.update');

                Route::get('opd/due/print/{id}', 'OutDoorRegistrationController@opdDuePrint')
                    ->name('opd.due.print');

                Route::get('opd/test/barcode/print/{id}', 'OutDoorRegistrationController@opdTestBarcodePrint')
                    ->name('opd.test.barcode.print');

                Route::post('opd/due/store', 'OutDoorRegistrationController@opdDueStore')
                    ->name('opd.due.store');

                Route::post('record/update/check', 'OutDoorRegistrationController@recordUpdateCheck')
                    ->name('record.update.check');

                // Outdoor Route End

                // Share Holder Route Start

                Route::resource('share_holders', 'ShareHolderController');
                Route::get('share_holder_fetch', 'ShareHolderController@fetchSharHolder')
                    ->name('share_holder.fetch');

                Route::get('opd_share_holder_report', 'ShareHolderController@opdShareHolderReport')
                    ->name('opd.share_holder_report');

                Route::post('opd_share_holder_report_search', 'ShareHolderController@opdShareHolderReportSearch')
                    ->name('opd.share_holder_report.search');


                Route::get('opd_test_discount_management_report', 'ShareHolderController@opdTestDiscountManagementReport')
                    ->name('opd.test_discount_management_report');

                Route::post('opd_test_discount_management_search', 'ShareHolderController@opdTestDiscountManagementSearch')
                    ->name('opd.test_discount_management.search');


                Route::get('opd_test_discount_other_report', 'ShareHolderController@opdTestDiscountOtherReport')
                    ->name('opd.test_discount_other_report');

                Route::post('opd_test_discount_management_search', 'ShareHolderController@opdTestDiscountOtherSearch')
                    ->name('opd.test_discount_other.search');
                // Share Holder Route End


                // Technologist Route Start
                Route::resource('technologists', 'TechnologistController');

                Route::put('technologist_data_update', 'TechnologistController@technologistDataUpdate')
                    ->name('technologist_data.update');

                Route::get('technologist_active_inactive/{id}', 'TechnologistController@technologistActiveInactive')
                    ->name('technologist.active.inactive');
                // Technologist Route Start

            });

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

                Route::resource('customers', 'CustomerController'); // Not Use Further after Store Use.

                Route::resource('store', 'StoreController');


                Route::resource('employee-leaves', 'EmpLeaveController')->except(['show']);


                // Task Only Do user
                Route::resource('task', 'TaskController');
                Route::resource('support', 'SupportController')->except(['edit', 'update']);


                // Warehouse

                Route::resource('warehouse', 'WarehouseController');
                Route::get('warehouse-history', 'WarehouseStockController@index')
                    ->name('warehouse.history.index');
                Route::get('warehouse-transfer-list', 'WarehouseStockController@transfer_list')
                    ->name('warehouse.history.transfer_list');
                Route::get('warehouse-history/show/{id}', 'WarehouseStockController@show')
                    ->name('warehouse.history.show');
                Route::get('warehouse/unload/{id}', 'WarehouseStockController@unload')
                    ->name('warehouse.unload');
                Route::get('warehouse/load/{id}', 'WarehouseStockController@load')
                    ->name('warehouse.load');
                Route::get('warehouse-transfer', 'WarehouseStockController@transfer')
                    ->name('warehouse.transfer');
                Route::post('warehouse/unload', 'WarehouseStockController@unload_store')
                    ->name('warehouse.unload.store');
                Route::post('warehouse/load', 'WarehouseStockController@load_store')
                    ->name('warehouse.load.store');
                Route::post('warehouse/transfer', 'WarehouseStockController@transfer_store')
                    ->name('warehouse.transfer.store');

                Route::get('warehouse-load-not-done', 'WarehouseStockController@load_not_done')
                    ->name('warehouse.load_not_done');
                Route::get('warehouse-unload-not-done', 'WarehouseStockController@unload_not_done')
                    ->name('warehouse.unload_not_done');


                /*
                 * Start Sharer Controller
                 *  Here Sharer Means:  Customer/ Supplier
                 *  Sharer Manage Customers/Supplier in ONE Controller and Model
                 */

                Route::resource('sharer', 'SharerController')->except(['create']);
                Route::get(
                    'sharer/create/{type}',
                    ['as' => 'sharer.create', 'uses' => 'SharerController@create']
                );
                Route::get(
                    'customer-list',
                    ['as' => 'sharer.customer_list', 'uses' => 'SharerController@customer_list']
                );
                Route::get(
                    'supplier-list',
                    ['as' => 'sharer.supplier_list', 'uses' => 'SharerController@supplier_list']
                );
                Route::get(
                    'broker-list',
                    ['as' => 'sharer.broker_list', 'uses' => 'SharerController@broker_list']
                );


                // Start Transaction Controller
                Route::resource('transaction', 'TransactionController')->except(['create']);
                Route::get('manage-daily-sheet', ['as' => 'transaction.manage_daily_sheet', 'uses' => 'TransactionController@manage_daily_sheet']);
                Route::get('transaction/create/{type}', ['as' => 'transaction.create', 'uses' => 'TransactionController@create']);
                Route::post('transaction/income', ['as' => 'transaction.incomeStore', 'uses' => 'TransactionController@incomeStore']);
                Route::get('transaction/transfer/create', ['as' => 'transaction.transfer.create', 'uses' => 'TransactionController@transfer']);
                Route::post('transaction/transfer/save', ['as' => 'transaction.transfer.save', 'uses' => 'TransactionController@saveTransfer']);
                Route::get('transaction/transfer/list', ['as' => 'transaction.transfer.list', 'uses' => 'TransactionController@listTransfer']);


                // Start General Ledger Controller
                Route::get('general-ledgers', ['as' => 'general_ledger.search', 'uses' => 'GeneralLedgerController@search']);
                Route::get('general-ledger/list', ['as' => 'general_ledger.list_ledger', 'uses' => 'GeneralLedgerController@list_ledger']);
                Route::get('general-ledger/print/{code}', ['as' => 'general_ledger.print', 'uses' => 'GeneralLedgerController@print_gl']);
                Route::resource('general_ledger', 'GeneralLedgerController')->only(['show', 'index']);


                Route::post('account-type-save', ['as' => 'account_type.save', 'uses' => 'CommonController@saveAccountType']);
                Route::post('customer-save', ['as' => 'customer.save', 'uses' => 'CommonController@saveCustomer']);
                Route::post('payer-search', ['as' => 'payer.search', 'uses' => 'CommonController@payerSearch']);
                Route::post('get-employee-details', ['as' => 'search.employee_details', 'uses' => 'CommonController@employee_details']);


                Route::get('sale-commission-paid', ['as' => 'sale_commission_paid', 'uses' => 'CommissionController@sale_commission_paid']);

                Route::post('sale-commission-paid', ['as' => 'sale_commission_paid.store', 'uses' => 'CommissionController@save_sale_commission_paid']);


                Route::get('employee-salary-paid', ['as' => 'employee_salary_paid', 'uses' => 'EmployeeSalaryController@employee_salary_paid']);

                Route::post('employee-salary-paid', ['as' => 'employee_salary_paid.store', 'uses' => 'EmployeeSalaryController@save_employee_salary_paid']);


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
                     *   Stock Report Start
                     *   All Stock Manage by StockReportController
                     */

                    Route::get('daily-stocks', ['as' => 'daily_stocks', 'uses' => 'StockReportController@daily_stocks']);
                    Route::get('stocks', ['as' => 'stocks', 'uses' => 'StockReportController@stocks']);
                    Route::get('dealer-stocks', ['as' => 'dealer_stocks', 'uses' => 'StockReportController@dealer_stocks']);
                    Route::get('dealer-daily-stocks', ['as' => 'dealer_daily_stocks', 'uses' => 'StockReportController@dealer_daily_stocks']);
                    Route::get('total-stocks', ['as' => 'total_stocks', 'uses' => 'StockReportController@total_stocks']);


                    Route::get('daily-warehouse-stocks', ['as' => 'daily_warehouse_stocks', 'uses' => 'WarehouseStockReportController@daily_stocks']);
                    Route::get('warehouse-stocks', ['as' => 'warehouse_stocks', 'uses' => 'WarehouseStockReportController@stocks']);
                    Route::get('warehouse-type-stocks', ['as' => 'warehouse_type_stocks', 'uses' => 'WarehouseStockReportController@warehouse_type_stocks']);
                    Route::get('warehouse-total-stocks', ['as' => 'warehouse_total_stocks', 'uses' => 'WarehouseStockReportController@total_stocks']);

                    Route::get('reconcile-gain-stocks', ['as' => 'gain_reconcile_stocks', 'uses' => 'StockReportController@gain_reconcile_stocks']);
                    Route::get('reconcile-loss-stocks', ['as' => 'loss_reconcile_stocks', 'uses' => 'StockReportController@loss_reconcile_stocks']);
                    Route::get('daily-stock-report-by-requisition-and-damage', ['as' => 'daily_stock_report_by_requisition_damage', 'uses' => 'StockReportController@daily_stock_report_by_requisition_damage']);
                    Route::get('daily-stock-by-rsrd', ['as' => 'daily_stock_by_rsrd', 'uses' => 'StockReportController@daily_stock_by_rsrd']);
                    //  End Stock Report


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


                    Route::get('new-trail-balance', ['as' => 'new_trail_balance', 'uses' => 'SummaryReportController@new_trail_balance']);
                    Route::get('trail-balance', ['as' => 'trail_balance', 'uses' => 'SummaryReportController@trail_balance']);
                    Route::get('trail-balance-v2', ['as' => 'trail_balance_v2', 'uses' => 'SummaryReportController@trail_balance_v2']);
                    Route::get('ledger-book', ['as' => 'ledger_book', 'uses' => 'SummaryReportController@ledger_book']);
                    Route::get('ledger-book-by-manager', ['as' => 'ledger_book_by_manager', 'uses' => 'SummaryReportController@ledger_book_by_manager']);
                    Route::get('ledger-book-by-sharer', ['as' => 'ledger_book_by_sharer', 'uses' => 'SummaryReportController@ledger_book_by_sharer']);
                    //            Route::get('daily-sheet', ['as' => 'daily_sheet', 'uses' => 'SummaryReportController@daily_sheet']);
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
                    //                    Route::get('cash-flow-statement', ['as' => 'cash_flow', 'uses' => 'SummaryReportController@cash_flow']);
                    //                    Route::get('cash-book', ['as' => 'cash_book', 'uses' => 'SummaryReportController@cash_book']);
                    //                    Route::get('cost-profit', ['as' => 'cost_profit', 'uses' => 'SummaryReportController@cost_profit']);

                    Route::get('total-sales-purchases', ['as' => 'total-sales-purchases', 'uses' => 'SummaryReportController@total_sales_purchases_report']);


                    Route::get('sharer-sales-report', ['as' => 'sharer_sales_report', 'uses' => 'SummaryReportController@sharer_sales_report']);


                    Route::get('balance-profit', ['as' => 'balance-profit', 'uses' => 'SummaryReportController@balance_profit']);

                    //  End Summary Report


                    Route::get('cost/{type}', ['as' => 'cost', 'uses' => 'ReportController@cost_report']);
                    Route::get('sharer-due/{type}', ['as' => 'sharer_due', 'uses' => 'ReportController@sharer_due_report']);
                    Route::get('sharer-due-collection/{type}', ['as' => 'sharer_due_collection', 'uses' => 'ReportController@sharer_due_collection_report']);
                    Route::get('inventory-due/{type}', ['as' => 'inventory_due', 'uses' => 'ReportController@inventory_due_report']);

                    Route::get('total-sales-purchases', ['as' => 'total-sales-purchases', 'uses' => 'SummaryReportController@total_sales_purchases_report']);

                    /*
                     *   Purchase Report Start
                     *   All Purchase Report Manage by PurchaseReportController
                     */
                    Route::get('purchase-report-by-product/{type}', ['as' => 'product_purchase_report', 'uses' => 'PurchaseReportController@purchase_report_by_product']);
                    Route::get('warehouse-purchase-report', ['as' => 'warehouse_purchase_report', 'uses' => 'PurchaseReportController@warehouse_purchase_report']);
                    Route::get('supplier-purchase', ['as' => 'supplier_purchase', 'uses' => 'PurchaseReportController@supplier_purchase_report']);
                    Route::get('purchase-return-report', ['as' => 'purchase_return_report', 'uses' => 'PurchaseReportController@purchase_return_report']);
                    Route::get('purchase', ['as' => 'purchase', 'uses' => 'PurchaseReportController@purchase_report']);
                    Route::get('total-purchases-as-per-day', ['as' => 'total-purchases-as-per-day', 'uses' => 'PurchaseReportController@daywise_total_purchases']);

                    //  End Purchase Report


                    /*
                     *   Sale Report Start
                     *   All Sale Report Manage by SaleReportController
                     */
                    Route::get('sale-profit-report', ['as' => 'sale_profit_report', 'uses' => 'SaleReportController@sale_profit_report']);
                    Route::get('sale-report-by-product/{type}', ['as' => 'sale_report_by_product', 'uses' => 'SaleReportController@sale_report_by_product']);
                    Route::get('sale-report-by-customer', ['as' => 'customer_sale', 'uses' => 'SaleReportController@customer_sale_report']);
                    Route::get('sale-return-report', ['as' => 'sale_return_report', 'uses' => 'SaleReportController@sale_return_report']);
                    Route::get('sale', ['as' => 'sale', 'uses' => 'SaleReportController@sale_report']);
                    Route::get('total-sales-as-per-day', ['as' => 'total-sales-as-per-day', 'uses' => 'SaleReportController@daywise_total_sales']);
                    Route::get('warehouse-sale-report', ['as' => 'warehouse_sale_report', 'uses' => 'SaleReportController@warehouse_sale_report']);
                    Route::get('sales-mini-statement', ['as' => 'sales-mini-statement', 'uses' => 'SaleReportController@sales_mini_statement']);

                    // End Sale Report

                    Route::get('project-expenses-report', ['as' => 'project_expenses_report', 'uses' => 'ProjectExpenseReportController@project_expense_report']);
                    Route::get('project-profit-report', ['as' => 'project_profit_report', 'uses' => 'ProjectExpenseReportController@project_profit_report']);


                    Route::get('requisition-report/', ['as' => 'requistion_report', 'uses' => 'RequisitionReportController@requistion_report']);
                    Route::get('requisition-by-salesman/', ['as' => 'salesman_requistion_report', 'uses' => 'RequisitionReportController@requisition_by_salesman']);

                    Route::get('employee-attendance', ['as' => 'employee-attendance', 'uses' => 'AttendanceReportController@index']);


                    Route::get('sale-commission', ['as' => 'sale-commission', 'uses' => 'SaleCommissionReportController@sale_commission']);
                    Route::get('paid-sale-commission', ['as' => 'paid-sale-commission', 'uses' => 'SaleCommissionReportController@paid_sale_commission']);


                    Route::get('account-day-wise-last-balance', ['as' => 'account_day_wise_last_balance', 'uses' => 'ReportController@account_day_wise_last_balance']);
                });

                // Start Journal Entry Controller
                Route::get('journal-entry/print/{code}', ['as' => 'journal_entry.print', 'uses' => 'JournalEntryController@print_journal_entry']);
                Route::resource('journal_entry', 'JournalEntryController');
                // End Journal Entry Controller

            });


            Route::resource('quotation_terms', 'QuotationTermController')->except(['show']);
            Route::resource('quotation_sub_terms', 'QuotationSubTermController')->except(['show']);
            Route::resource('quote_attentions', 'QuotationCustomerController')->except(['show']);
            Route::resource('quote_company', 'QuotationCompanyController')->except(['show']);
            Route::resource('quoting', 'QuotingPersonController')->except(['show']);
            Route::resource('quotations', 'QuotationController');
            Route::resource('purchase_quotations', 'QuotationPurchaseController');
            Route::post('purchase_quotations/multi_supplier_based_store', ['as' => 'purchase_quotations.multi_supplier_based_store', 'uses' => 'QuotationPurchaseController@multi_supplier_based_store']);
            Route::resource('sale_quotations', 'QuotationSaleController');
            Route::get('quotations-print/{id}', ['as' => 'quotations.print', 'uses' => 'QuotationController@print_quotation']);
            Route::get('quotations-add-others-transaction', ['as' => 'quotations.add-others-transaction', 'uses' => 'QuotationController@addQuotationOthersTransaction']);
            Route::post('save-quotations-add-others-transaction', ['as' => 'quotations.save-others-transaction', 'uses' => 'QuotationController@saveQuotationOthersTransaction']);
            Route::get('quotations-profit/{id}', ['as' => 'quotations.profit', 'uses' => 'QuotationController@profit_quotation']);
            Route::delete('delete-others-transaction/{id}', ['as' => 'quotations.delete-others-transaction', 'uses' => 'QuotationController@destroyTransactionAttach']);


            Route::resource('requisition', 'RequisitionController');
            Route::get('requisition/approved/{id}', 'RequisitionController@requisition_approved')->name('requisition_approved');
            Route::get('requisition-print/{id}', ['as' => 'requisition.print_requisition', 'uses' => 'RequisitionController@print_requisition']);

            Route::get('sale-requisition-print/{id}', ['as' => 'sales_requisitions.print_requisition', 'uses' => 'SalesRequisitionController@print_requisition']);
            Route::resource('sales_requisitions', 'SalesRequisitionController');
            Route::get('sale-requisition/{id}', ['as' => 'sales_requisitions.requisition', 'uses' => 'SalesRequisitionController@sale_requisition']);
            Route::get('sale-from-requisition', ['as' => 'sales.from-requisition', 'uses' => 'SalesRequisitionController@requisitions']);
            Route::get('sale-by-requisition', ['as' => 'sales.by-requisition', 'uses' => 'SalesRequisitionController@sale_requisitions_list']);
            Route::get('sales-requisitions-dealer-index', ['as' => 'sales_requisitions.dealer_index', 'uses' => 'SalesRequisitionController@dealer_index']);


            // Start Purchase Controller
            Route::resource('purchase', 'PurchaseController');
            Route::get('purchase-print/{id}', ['as' => 'purchase.print_purchases', 'uses' => 'PurchaseController@print_purchases']);
            Route::get('purchase-requisition/{id}', ['as' => 'purchase.requisition', 'uses' => 'PurchaseRequisitionController@purchase_requisition']);
            Route::get('purchase-from-requisition', ['as' => 'purchase.from-requisition', 'uses' => 'PurchaseRequisitionController@requisitions']);
            Route::resource('purchase-requisition', 'PurchaseRequisitionController', ['only' => ['index']]);

            Route::get('purchase-item-serial/{id}/{item_id}', ['as' => 'purchase.item_serial', 'uses' => 'PurchaseController@entry_item_serial']);
            Route::post('purchase-item-serial-store', ['as' => 'purchase.item_serial_store', 'uses' => 'PurchaseController@item_serial_store']);


            // Start Purchase By Requisition Controller
            Route::resource('purchases_by_requisition', 'PurchasesByRequisitionController');
            Route::get('print-purchase-by-requisition/{id}', ['as' => 'purchase_by_requisition.print_purchases', 'uses' => 'PurchasesByRequisitionController@print_purchases']);


            // Start Purchase Due Controller
            Route::get('purchase-due-list', ['as' => 'purchase.due_list', 'uses' => 'PurchaseDueController@due_list']);
            Route::get('purchase-due-payment/{id}', ['as' => 'purchase.due_payment', 'uses' => 'PurchaseDueController@due_payment']);
            Route::post('purchase-due-receive/{id}', ['as' => 'purchase.receive_due_payment', 'uses' => 'PurchaseDueController@receive_due_payment']);

            // Start Purchase Return Controller
            Route::resource('purchase_return', 'PurchaseReturnController');
            Route::get('purchase_return/view_returns/{id}/{code}', ['as' => 'purchase_return.view_returns', 'uses' => 'PurchaseReturnController@view_returns']);

            // Start Sale Controller
            Route::resource('sales', 'SalesController');
            Route::get('print-sale/{id}', ['as' => 'sales.print_sale', 'uses' => 'SalesController@print_sale']);
            Route::get('print-calan/{id}', ['as' => 'sales.print_calan', 'uses' => 'SalesController@print_calan']);


            Route::resource('dealer_sales', 'DealerSaleController');
            Route::get('print-dealer-sale/{id}', ['as' => 'dealer_sales.print_sale', 'uses' => 'DealerSaleController@print_sale']);


            Route::resource('warehouse_sales', 'WarehouseSaleController');
            Route::resource('warehouse_purchases', 'PurchaseWarehouseController');


            // Start Sale By Requisition Controller
            Route::resource('sales_by_requisition', 'SalesByRequisitionController');
            Route::get('print-sale-by-requisition/{id}', ['as' => 'sales_by_requisition.print_sale', 'uses' => 'SalesByRequisitionController@print_sale']);
            Route::get('print-calan-by-requisition/{id}', ['as' => 'sales_by_requisition.print_calan', 'uses' => 'SalesByRequisitionController@print_calan']);


            // Start Sale Return Controller
            Route::get('sales-return-list', ['as' => 'sales.sales_return_list', 'uses' => 'SaleReturnController@sales_return_list']);
            Route::get('sales-return/{id}', ['as' => 'sales.sales_return', 'uses' => 'SaleReturnController@sales_return']);
            Route::put('sales-return-update/{id}', ['as' => 'sales.sales_return_update', 'uses' => 'SaleReturnController@sales_return_update']);
            Route::get('sales/view_return/{id}/{code}', ['as' => 'sales.view_return', 'uses' => 'SaleReturnController@sale_return_view']);


            // Start Sale Damage Return Controller
            //            Route::get('damage-product-list', ['as' => 'sales.sales_return_list_damage', 'uses' => 'damagerController@damage_list']);
            //            Route::get('sales-damage_return/{id}', ['as' => 'sales.sales_return_damage', 'uses' => 'damagerController@sales_return']);
            //            Route::put('sales-damage-update/{id}', ['as' => 'sales.sales_return_update_damage', 'uses' => 'damagerController@sales_return_update']);
            //            Route::get('sales/view_damage/{id}/{code}', ['as' => 'sales.view_damage', 'uses' => 'damagerController@sale_return_view']);


            // Start Sale Due Controller
            Route::get('sales-due-list', ['as' => 'sales.due_list', 'uses' => 'SaleDueController@due_list']);
            Route::get('sales-due-payment/{id}', ['as' => 'sales.due_payment', 'uses' => 'SaleDueController@due_payment']);
            Route::post('sales-due-receive/{id}', ['as' => 'sales.receive_due_payment', 'uses' => 'SaleDueController@receive_due_payment']);


            // Start Sale Due Controller
            Route::get('dealer-sales-due-list', ['as' => 'dealer_sales.due_list', 'uses' => 'DealerSaleDueController@due_list']);
            Route::get('dealer-sales-due-payment/{id}', ['as' => 'dealer_sales.due_payment', 'uses' => 'DealerSaleDueController@due_payment']);
            Route::post('dealer-sales-due-receive/{id}', ['as' => 'dealer_sales.receive_due_payment', 'uses' => 'DealerSaleDueController@receive_due_payment']);

            // Start Whole Sale Controller
            Route::get('whole-sale-create', ['as' => 'sales.whole_sale_create', 'uses' => 'WholeSaleController@wholeSaleCreate']);
            Route::post('whole-sale-save', ['as' => 'sales.whole_sale_store', 'uses' => 'WholeSaleController@storeWholeSale']);

            // Start POS Sale Controller
            Route::get('sale/pos-create', ['as' => 'sales.pos_create', 'uses' => 'PosSaleController@Create']);
            Route::post('sale/pos-save', ['as' => 'sales.pos_store', 'uses' => 'PosSaleController@store']);


            Route::resource('banks', 'BankController')->except(['show', 'destroy']);
            Route::resource('bank_branch', 'BankBranchController')->except(['show', 'destroy']);
            Route::resource('cheque_entries', 'ChequeEntryController')->except(['show']);
            Route::resource('reconciliation', 'ReconciliationController')->except(['edit', 'update']);
            Route::get('reconciliation/create/{type}', ['as' => 'reconciliation.create', 'uses' => 'ReconciliationController@create']);
            Route::get('today-cheque-list', ['as' => 'cheque_entries.today_cheque_list', 'uses' => 'ChequeEntryController@chequeTodaysQueue']);
            Route::post('cheque-status-change', ['as' => 'cheque_entries.change_status', 'uses' => 'ChequeEntryController@changeChequeStatus']);
        });
    });
});
