<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerStockReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_stock_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->string('product_code', 50);
            $table->string('product_name');
            $table->double('opening_stock');
            $table->double('purchase_qty')->default(0);
            $table->double('sale_qty')->default(0);
            $table->double('purchase_return_qty')->default(0);
            $table->double('sale_return_qty')->default(0);
            $table->double('closing_stock')->default(0);
            $table->date('date');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('dealer_id');
            $table->timestamps();


            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dealer_id')->references('id')->on('users')
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
        Schema::dropIfExists('dealer_stock_reports');
    }
}
