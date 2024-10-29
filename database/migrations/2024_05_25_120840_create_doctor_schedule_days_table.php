<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorScheduleDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_schedule_days', function (Blueprint $table) {
            $table->increments('id');
            $table->string('day_name')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->unsignedInteger('doctor_schedule_id')->nullable();

            $table->foreign('doctor_schedule_id')->references('id')->on('doctor_schedules')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('doctor_schedule_days');
    }
}