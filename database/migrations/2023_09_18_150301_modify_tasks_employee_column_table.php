<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTasksEmployeeColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('tasks', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tasks` DROP FOREIGN KEY  `tasks_employee_info_id_foreign`;");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tasks` DROP INDEX `tasks_employee_info_id_foreign`;");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tasks` CHANGE `employee_info_id` `employee_info_id` INT(10) UNSIGNED NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
}