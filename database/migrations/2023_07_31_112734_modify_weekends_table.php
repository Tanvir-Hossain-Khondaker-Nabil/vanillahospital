<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWeekendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekends', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedSmallInteger('month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekends', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('month');
        });
    }
}
