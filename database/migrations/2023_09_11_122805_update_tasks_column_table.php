<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTasksColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {

            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tasks` CHANGE `priority` `priority` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '-,minor,high,major';");

        });

        Schema::create('task_employee_statuses', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('employee_info_id');
            $table->unsignedInteger('updated_by')->nullable();
            $table->string('status', 20);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();

        });

        Schema::create('task_comments', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('employee_info_id');
            $table->string('comments', 1000)->nullable();
            $table->timestamps();

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
