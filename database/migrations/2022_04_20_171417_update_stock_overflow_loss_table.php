<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStockOverflowLossTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_overflow_reconcile', function (Blueprint $table) {
            $table->date('closing_date')->nullable();
        });
        Schema::table('loss_stock_reconcile', function (Blueprint $table) {
            $table->date('closing_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_overflow_reconcile', function (Blueprint $table) {
            $table->dropColumn('closing_date');
        });
        Schema::table('loss_stock_reconcile', function (Blueprint $table) {
            $table->dropColumn('closing_date');
        });
    }
}
