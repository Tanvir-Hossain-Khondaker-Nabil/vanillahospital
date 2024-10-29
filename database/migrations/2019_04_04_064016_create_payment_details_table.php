<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('transaction_details_id');
            $table->string('number')->nullable()->comment('Check/ Credit Card/ Master Card/ Mobile Banking/ Any number related to payment');
            $table->string('issuer_name');
            $table->string('receiver_name')->nullable();
            $table->string('short_description');
            $table->date('date')->comment('Issue/Expire date');
            $table->date('pass_date')->nullable();
            $table->date('provide_date')->nullable();
            $table->string('email')->nultlable();
            $table->unsignedInteger('company_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('payment_details');
    }
}
