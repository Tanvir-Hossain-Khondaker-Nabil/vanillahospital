<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionTableReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_purchases', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id')->nullable();
        });
        Schema::table('sales_return', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_purchases', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
        Schema::table('sales_return', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
}
