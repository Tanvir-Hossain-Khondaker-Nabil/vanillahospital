<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTableModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advance_shift', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('attendence_master', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('cashadvance', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('deductions', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('overtime', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('salary_management', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
        Schema::table('emp_leave', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('advance_shift', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('attendence_master', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('cashadvance', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('deductions', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('overtime', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('salary_management', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('emp_leave', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
