<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requisition_id');
            $table->unsignedInteger('item_id');
            $table->double('qty');
            $table->string('unit', 10);
            $table->double('price');
            $table->double('total_price');
            $table->double('last_purchase_qty')->nullable();
            $table->double('last_purchase_price')->nullable();
            $table->double('available_stock')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('purchase_status')->default(0);
            $table->timestamps();

            $table->foreign('requisition_id')->references('id')->on('requisitions');
            $table->foreign('item_id')->references('id')->on('items');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition_details');
    }
}
