<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountBalanceHistoryTransactionIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_heads_balance_histories', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id')->nullable();

            $table->foreign('transaction_id')->references('id')->on('transactions')
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
        Schema::table('account_heads_balance_histories', function (Blueprint $table) {

            $table->dropForeign('account_heads_balance_histories_transaction_id_foreign');
            $table->dropColumn('transaction_id');
        });
    }
}
