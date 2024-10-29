<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bank_id');
            $table->string('cheque_no', 15)->unique();
            $table->string('payer_name');
            $table->double('amount');
            $table->date('giving_date');
            $table->date('issue_date');
            $table->string('file',100)->nullable();
            $table->string('note')->nullable();
            $table->unsignedInteger('sharer_id')->nullable();
            $table->enum('issue_status',['pending','completed','canceled'])->default('pending');
            $table->unsignedSmallInteger('attempted_count')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sharer_id')->references('id')->on('suppliers_or_customers')
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
        Schema::dropIfExists('cheque_entries');
    }
}
