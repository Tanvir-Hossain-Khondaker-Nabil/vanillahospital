<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmployeeLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_leave', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `emp_leave` CHANGE `leave_id` `leave_id` INT(11) NULL;");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `emp_leave` CHANGE `l_note` `l_note` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");

            $table->string('attachment', 300)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_leave', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
}
