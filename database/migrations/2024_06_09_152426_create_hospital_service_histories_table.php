<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalServiceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_service_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('marketing_officer_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->integer('qty');
            $table->string('order_id');
            $table->double('price');
            $table->integer('discount_type')->nullable()->comment('0=taka,1=parcentage');
            $table->integer('discount_amount')->nullable();
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
        Schema::dropIfExists('hospital_service_histories');
    }
}
