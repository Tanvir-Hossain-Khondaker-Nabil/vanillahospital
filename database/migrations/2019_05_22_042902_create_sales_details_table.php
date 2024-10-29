<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('company_id');
            $table->string('unit');
            $table->double('qty');
            $table->double('price');
            $table->double('discount')->nullable()->default(0);
            $table->string('description')->nullable();
            $table->double('warranty')->nullable();
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
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
        Schema::dropIfExists('sales_details');
    }
}
