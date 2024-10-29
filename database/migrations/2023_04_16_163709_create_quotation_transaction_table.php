<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quotation_id');
            $table->unsignedInteger('transaction_id');
            $table->string('transaction_type',5);
            $table->string('note', 400)->nullable();
            $table->unsignedInteger('created_by');
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
        Schema::dropIfExists('quotation_transactions');
    }
}
