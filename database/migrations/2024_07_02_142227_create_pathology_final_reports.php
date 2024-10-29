<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathologyFinalReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathology_final_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description',5000)->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('sub_test_group_id')->nullable();
            $table->unsignedInteger('out_door_registration_id')->nullable();
            $table->unsignedInteger('out_door_patient_test_id')->nullable();

            $table->foreign('out_door_registration_id')->references('id')->on('out_door_registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('out_door_patient_test_id')->references('id')->on('out_door_patient_tests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_test_group_id')->references('id')->on('sub_test_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pathology_final_reports');
    }
}