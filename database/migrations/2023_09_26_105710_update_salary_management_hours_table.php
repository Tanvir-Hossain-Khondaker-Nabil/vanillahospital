<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalaryManagementHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_management', function (Blueprint $table) {
            $table->float("total_hours_worked")->nullable();
            $table->float("total_actual_hours")->nullable();
            $table->float("total_overtime")->nullable();
            $table->string("salary_system", 10)->default('Monthly')->nullable();
            $table->float("tax")->default(11);
            $table->double("tax_amount")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_management', function (Blueprint $table) {
            $table->dropColumn("total_hours_worked");
            $table->dropColumn("total_actual_hours");
            $table->dropColumn("total_overtime");
            $table->dropColumn("salary_system");
            $table->dropColumn("tax");
            $table->dropColumn("tax_amount");
        });
    }
}
