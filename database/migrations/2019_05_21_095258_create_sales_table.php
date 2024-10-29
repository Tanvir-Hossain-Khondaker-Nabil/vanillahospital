<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sale_code', 30);
            $table->date('date');
            $table->double('total_price');
            $table->double('paid_amount');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount');
            $table->double('tax')->nullable();
            $table->string('notation')->nullable();
            $table->unsignedInteger('cash_or_bank_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->unsignedInteger('delivery_type_id')->nullable();
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_type_id')->references('id')->on('delivery_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('suppliers_or_customers')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
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
        Schema::dropIfExists('sales');
    }
}
