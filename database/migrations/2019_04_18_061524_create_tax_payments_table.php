<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('input_tax')->default(0.0);
            $table->double('output_tax')->default(0.0);
            $table->double('amount');
            $table->date('vat_date');
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
        Schema::dropIfExists('tax_payments');
    }
}
