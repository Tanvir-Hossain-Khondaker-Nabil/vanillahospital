<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOutDoorRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('out_door_registrations', function (Blueprint $table) {
            // $table->string('day')->nullable();
            // $table->string('month')->nullable();
            // $table->string('year')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('out_door_registrations', function (Blueprint $table) {
            //
        });
    }
}