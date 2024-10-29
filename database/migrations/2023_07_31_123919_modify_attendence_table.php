<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_attendences', function (Blueprint $table) {
            $table->string('late_time')->nullable();
            $table->string('attend_status',10)->default("P");
            $table->unsignedInteger('assign_shift_id')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->boolean('shift_change')->default(0);
            $table->dateTime('overtime')->nullable();
            $table->float('total_overtime')->nullable();
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::table('employee_attendences', function (Blueprint $table) {
            //
        });
    }
}
