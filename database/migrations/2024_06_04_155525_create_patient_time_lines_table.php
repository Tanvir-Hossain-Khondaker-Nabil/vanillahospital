<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTimeLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_time_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger('patient_id')->default(0);
            $table->unsignedBigInteger('order_id')->default(0);
            $table->integer('type')->default(0)->comment('1 = registration 2=cabin transfer 3= release 4=operation');
            $table->integer('cabin_no')->default(0);
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
        Schema::dropIfExists('patient_time_lines');
    }
}
