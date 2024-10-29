<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_requisition_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sales_requisition_id');
            $table->unsignedInteger('item_id');
            $table->double('qty');
            $table->string('unit', 10);
            $table->double('price');
            $table->double('total_price');
            $table->double('last_purchase_qty')->nullable();
            $table->double('last_purchase_price')->nullable();
            $table->double('available_stock')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('sale_status')->default(0);
            $table->timestamps();

//            $table->foreign('sales_requisition_id')->references('id')->on('sales_requisitions');
//            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_requisition_details');
    }
}
