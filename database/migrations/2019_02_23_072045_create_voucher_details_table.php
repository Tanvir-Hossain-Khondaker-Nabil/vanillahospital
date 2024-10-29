<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voucher_id')->unsigned();
            $table->string('product_name', 50);
            $table->double('price');
            $table->integer('qty');
            $table->string('description');
            $table->timestamps();

            $table->foreign('voucher_id')->references('id')->on('vouchers')
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
        Schema::dropIfExists('voucher_details');
    }
}
