<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpdFinalBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipd_final_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("company_id");
            $table->integer('p_id');
            $table->string('invoice_order_id')->nullable();
            $table->double('admission_fee')->default(0);
            $table->double('advance_payment')->default(0);
            $table->double('admission_fee_paid')->default(0);
            $table->double('admission_fee_discount')->default(0);
            $table->double('total_vat')->default(0);
            $table->double('total_discount')->default(0);
            $table->double('total_amount')->default(0);
            $table->double('amount_cabin')->default(0);
            $table->double('amount_service')->default(0);
            $table->double('amount_test')->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->double('total_paid')->default(0);
            $table->timestamp('released_date')->nullable();
            $table->double('service_charge')->default(0);
            $table->double('bill_count_day')->default(0);
            $table->double('pharmacy_total')->default(0);
            $table->integer('discharge_status')->default(0);
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
        Schema::dropIfExists('ipd_final_bills');
    }
}
