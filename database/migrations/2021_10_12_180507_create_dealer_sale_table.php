<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sale_code', 30);
            $table->string('memo_no')->nullable();
            $table->string('chalan_no')->nullable();
            $table->date('date');
            $table->double('total_price');
            $table->double('paid_amount');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount');
            $table->double('amount_to_pay');
            $table->double('due')->default(0);
            $table->double('grand_total');
            $table->double('total_discount')->default(0);
            $table->double('unload_cost')->default(0);
            $table->double('transport_cost')->default(0);
            $table->double('shipping_charge')->default(0);
            $table->double('tax')->nullable();
            $table->string('membership_card')->nullable();
            $table->string('notation')->nullable();
            $table->double('vehicle_number')->nullable();
            $table->unsignedInteger('cash_or_bank_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->unsignedInteger('delivery_type_id')->nullable();
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('dealer_id');
            $table->unsignedInteger('saler_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('delivery_type_id')->references('id')->on('delivery_types');
            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts');
            $table->foreign('customer_id')->references('id')->on('suppliers_or_customers');
            $table->foreign('saler_id')->references('id')->on('users');
            $table->foreign('dealer_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('member_id')->references('id')->on('members');
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
        Schema::dropIfExists('dealer_sales');
    }
}
