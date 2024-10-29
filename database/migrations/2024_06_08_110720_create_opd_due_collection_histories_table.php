<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpdDueCollectionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opd_due_collection_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('net_total')->nullable();
            $table->integer('paid')->nullable();
            $table->integer('due')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->integer('discount')->nullable();
            $table->string('discount_ref')->nullable();

            $table->unsignedInteger('out_door_registration_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('opd_due_collection_histories');
    }
}