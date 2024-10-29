<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountHeadsBalanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_heads_balance_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_type_id');
            $table->string('account_head_name');
            $table->unsignedBigInteger('balance');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->date('date');
            $table->timestamps();


            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
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
        Schema::dropIfExists('account_heads_balance_histories');
    }
}
