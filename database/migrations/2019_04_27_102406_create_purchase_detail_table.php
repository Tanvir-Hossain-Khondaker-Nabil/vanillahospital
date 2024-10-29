<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('company_id');
            $table->double('qty');
            $table->string('unit', 10);
            $table->double('warranty')->nullable();
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->double('price');
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')
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
        Schema::dropIfExists('purchase_details');
    }
}
