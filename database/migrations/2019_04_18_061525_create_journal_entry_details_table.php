<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->increments('id');
            $table->date('document_date');
            $table->date('event_date');
            $table->unsignedInteger('tax_id')->nullable();
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('transaction_details_id');
            $table->unsignedInteger('account_type_id');
            $table->unsignedInteger('payment_method_id');
            $table->double('amount');
            $table->enum('transaction_type',['dr','cr']);
            $table->string('source_reference')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->timestamps();

            $table->foreign('tax_id')->references('id')->on('tax_payments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_details_id')->references('id')->on('transaction_details')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')
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
        Schema::dropIfExists('journal_entry_details');
    }
}
