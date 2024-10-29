<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProjectColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
//            $table->string('project_status')->default('client');
//            $table->string('client_id')->nullable();
//            $table->string('employee_id')->nullable();
//            $table->string('start_date')->nullable();
            $table->string('complete_status')->default('incomplete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('complete_status');
            $table->dropColumn('project_status');
            $table->dropColumn('client_id');
            $table->dropColumn('employee_id');
        });
    }
}
