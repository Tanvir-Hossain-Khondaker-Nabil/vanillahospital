<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareHolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('out_door_registrations', function (Blueprint $table) {

            $table->string('share_holder_type')->nullable()->comment('0=share holder,1=management, 2=others');

            $table->unsignedInteger('share_holder_id')->nullable();
            $table->foreign('share_holder_id')->references('id')->on('share_holders')->onUpdate('cascade')->onDelete('cascade');

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