<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('chalan');
            $table->string('memo_no');
            $table->double('total_price');
            $table->double('due_amount');
            $table->double('advance_amount');
            $table->double('paid_amount');
            $table->double('tax');
            $table->double('total_discount');
            $table->double('amt_to_pay');
            $table->unsignedInteger('cash_or_bank_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers_or_customers')
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
        Schema::dropIfExists('purchases');
    }
}
