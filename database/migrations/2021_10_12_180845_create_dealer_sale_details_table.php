<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_sale_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('dealer_id');
            $table->unsignedInteger('saler_id')->nullable();
            $table->string('unit');
            $table->double('qty');
            $table->double('price');
            $table->double('total_price')->nullable();
            $table->integer('available_stock')->nullable();
            $table->integer('last_sale_qty')->nullable();
            $table->double('pack_qty')->default(0);
            $table->double('free_qty')->default(0);
            $table->double('trade_price')->default(0);
            $table->double('carton')->default(0);
            $table->double('free')->default(0);
            $table->double('discount');
            $table->string('description')->nullable();
            $table->enum('sale_status',['sale','return','edit'])->default('sale');
            $table->double('warranty')->nullable();
            $table->date('date');
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->timestamps();

            $table->unsignedInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('saler_id')->references('id')->on('users');
            $table->foreign('dealer_id')->references('id')->on('users');
            $table->foreign('sale_id')->references('id')->on('dealer_sales');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_sale_details');
    }
}
