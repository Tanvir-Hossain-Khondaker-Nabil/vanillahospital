<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCompanyModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('default_country')->default(19);

            $table->string('salary_system',10)->default('monthly');
            $table->string('overtime_system', 10)->default('salary');
            $table->float('overtime_percentage')->default(1);
            $table->string('start_week',10)->default("Saturday");
            $table->string('end_week',10)->default("Thursday");
            $table->string('late_after_time', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('default_country');
            $table->dropColumn('salary_system');
            $table->dropColumn('overtime_system');
            $table->dropColumn('overtime_percentage');
            $table->dropColumn('start_week');
            $table->dropColumn('end_week');
            $table->dropColumn('late_after_time');
        });
    }
}
