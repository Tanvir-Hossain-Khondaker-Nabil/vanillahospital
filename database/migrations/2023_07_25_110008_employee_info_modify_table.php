<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeInfoModifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_info', function (Blueprint $table) {

//            ALTER TABLE `employee_info` DROP INDEX `employee_info_branch_id_foreign`
//            ALTER TABLE `employee_info` DROP FOREIGN KEY `employee_info_branch_id_foreign`

            // $table->dropForeign('branch_id');
            // $table->dropColumn('branch_id');
            // $table->dropForeign('upazilla_id');
            // $table->dropForeign('union_id');
            // $table->dropForeign('district_id');
            // $table->dropForeign('division_id');

            $table->dropForeign('employee_info_branch_id_foreign');
            $table->dropIndex('employee_info_branch_id_foreign');
            $table->dropForeign('employee_info_upazilla_id_foreign');
            $table->dropForeign('employee_info_union_id_foreign');
            $table->dropForeign('employee_info_district_id_foreign');
            $table->dropForeign('employee_info_division_id_foreign');
//
           $table->dropColumn('branch_id');

            $table->string('salary_system', 10)->default("monthly");
            $table->string('weekend_accept')->default("1")
                ->comment("0=off,1=on");

            $table->unsignedInteger('shift_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('passport_number',15)->nullable();
            $table->string('diving_license',15)->nullable();
            $table->string('pr_number',15)->nullable();
            $table->string('visa_copy',15)->nullable();
            $table->string('insurance_number',15)->nullable();
            $table->string('insurance_company')->nullable();
            $table->date('visa_expire')->nullable();
            $table->date('pr_expire')->nullable();
            $table->date('passport_expire')->nullable();
            $table->unsignedInteger('nationality')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_info', function (Blueprint $table) {
            $table->dropColumn('salary_system');
            $table->dropColumn('weekend_accept');
            $table->dropColumn('shift_id');
            $table->dropColumn('department_id');
            $table->dropColumn('passport_number');
            $table->dropColumn('diving_license');
            $table->dropColumn('pr_number');
            $table->dropColumn('visa_copy');
            $table->dropColumn('insurance_number');
            $table->dropColumn('insurance_company');
            $table->dropColumn('visa_expire');
            $table->dropColumn('pr_expire');
            $table->dropColumn('passport_expire');
            $table->dropColumn('nationality');
        });
    }
}