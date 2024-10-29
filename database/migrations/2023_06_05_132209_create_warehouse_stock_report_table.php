<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_stock_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->double('opening_stock');
            $table->double('load_qty')->default(0);
            $table->double('unload_qty')->default(0);
            $table->double('transfer_qty')->default(0);
            $table->double('closing_stock')->default(0);
            $table->date('date');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('company_id');
            $table->timestamps();


            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')
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
        Schema::dropIfExists('warehouse_stock_reports');
    }
}
