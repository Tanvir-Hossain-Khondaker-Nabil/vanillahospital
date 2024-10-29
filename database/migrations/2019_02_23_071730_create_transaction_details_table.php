<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_id');
            $table->smallInteger('transaction_category_id')->unsigned()->nullable();
            $table->integer('account_type_id')->unsigned()->nullable();
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->date('date');
            $table->double('amount');
            $table->enum('transaction_type',['dr','cr'])->default('dr');
            $table->text('description')->nullable();
            $table->text('short_description');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')
                ->onUpdate('cascade')->onDelete('SET NULL');
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
        Schema::dropIfExists('transaction_details');
    }
}
