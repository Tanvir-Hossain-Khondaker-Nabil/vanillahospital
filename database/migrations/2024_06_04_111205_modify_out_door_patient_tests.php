<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOutDoorPatientTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('out_door_patient_tests', function (Blueprint $table) {

            $table->unsignedInteger('sub_test_group_id')->nullable();
            $table->foreign('sub_test_group_id')->references('id')->on('sub_test_groups')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('test_group_id')->nullable();
            $table->foreign('test_group_id')->references('id')->on('test_groups')->onUpdate('cascade')->onDelete('cascade');

            $table->string('report_making_status')->nullable()->default(0)->comment('1=Ready, 0=Not Ready');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('out_door_patient_tests', function (Blueprint $table) {
            //
        });
    }
}