<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_code', 14);
            $table->integer('transaction_details_id')->unsigned();
            $table->integer('cash_or_bank_id')->unsigned();
            $table->date('date');
            $table->double('amount');
            $table->double('previous_balance')->default(0);
            $table->double('current_balance')->default(0);
            $table->enum('transaction_type',['dr','cr'])->default('dr');
            $table->enum('transaction_method',['Income','Transfer','Expense'])->default('Expense');
            $table->string('browser_history');
            $table->string('ip_address');
            $table->enum('flag',['add','edit','delete'])->default('add');
            $table->integer('member_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->unsignedInteger('company_id')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_details_id')->references('id')->on('transaction_details')
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
        Schema::dropIfExists('transaction_history');
    }
}
