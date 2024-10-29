<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutDoorPatientTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_door_patient_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('net_amount')->nullable();
            $table->string('vat')->nullable();
            $table->string('doctor_comission')->nullable();
            $table->string('sub_comission')->nullable();
            $table->unsignedInteger('out_door_registration_id')->nullable();


            $table->foreign('out_door_registration_id')->references('id')->on('out_door_registrations')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('out_door_patient_tests');
    }
}