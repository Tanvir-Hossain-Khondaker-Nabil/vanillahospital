<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_info', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('id');
            $table->string('first_name')->nullable()->after('id');
            $table->string('employeeID',30)->nullable()->after('id');
            $table->unsignedInteger('designation_id')->nullable()
                ->after('last_name');
            $table->unsignedInteger('region_id')->nullable()
                ->after('designation_id');
            $table->unsignedInteger('thana_id')->nullable()
                ->after('region_id');
            $table->unsignedInteger('area_id')->nullable()
                ->after('thana_id');
            $table->date('join_date')->nullable()->after('area_id');
            $table->double('salary')->nullable()->after('join_date');
            $table->float('commission')->nullable()->after('salary');


            $table->softDeletes();

//            $table->foreign('designation_id')->references('id')
//                ->on('designations')->onUpdate('cascade');
//            $table->foreign('region_id')->references('id')->on('regions')
//                ->onUpdate('cascade');
//            $table->foreign('thana_id')->references('id')
//                ->on('thanas')->onUpdate('cascade');
//            $table->foreign('area_id')->references('id')->on('areas')
//                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_info', function (Blueprint $table) {

            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('designation_id');
            $table->dropColumn('region_id');
            $table->dropColumn('thana_id');
            $table->dropColumn('area_id');
            $table->dropColumn('join_date');
            $table->dropColumn('salary');
            $table->dropColumn('commission');
        });
    }
}
