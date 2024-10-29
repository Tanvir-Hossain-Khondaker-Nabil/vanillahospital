<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOutDoorRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('out_door_registrations', function (Blueprint $table) {

            $table->string('opd_id')->nullable();
            $table->string('due_status')->nullable()->comment('1=due, 0=paid');

            $table->string('barcode_status')->nullable()->default(0)->comment('1=Ready, 0=Not Ready');
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