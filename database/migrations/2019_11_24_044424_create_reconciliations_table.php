<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_or_bank_id')->nullable();
            $table->unsignedInteger('sharer_id')->nullable();
            $table->unsignedInteger('account_type_id');
            $table->enum('transaction_type', ['dr','cr']);
            $table->string('notes')->nullable();
            $table->date('date');
            $table->double('amount');
            $table->string('transaction_code');
            $table->unsignedInteger('transaction_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->references('id')->on('transactions')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sharer_id')->references('id')->on('suppliers_or_customers')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
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
        Schema::dropIfExists('reconciliations');
    }
}
