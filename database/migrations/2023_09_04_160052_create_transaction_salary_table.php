<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->unsignedInteger("salary_id")->nullable();
        });
        Schema::table('salary_management', function (Blueprint $table) {
            $table->boolean("given_status")->default(0);
        });

        Schema::table('employee_info', function (Blueprint $table) {
            $table->unsignedInteger("account_type_id")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn("salary_id");
        });

        Schema::table('salary_management', function (Blueprint $table) {
            $table->dropColumn("given_status");
        });

        Schema::table('employee_info', function (Blueprint $table) {
            $table->dropColumn("account_type_id");
        });

    }
}
