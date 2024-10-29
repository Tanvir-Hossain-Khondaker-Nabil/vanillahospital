<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorComissionTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_comission_test', function (Blueprint $table) {
            $table->increments('id');
          
            $table->unsignedInteger('doctor_comission_id')->nullable();
            $table->unsignedInteger('sub_test_group_id')->nullable();

            $table->foreign('doctor_comission_id')->references('id')->on('doctor_comissions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_test_group_id')->references('id')->on('sub_test_groups')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('doctor_comission_test');
    }
}