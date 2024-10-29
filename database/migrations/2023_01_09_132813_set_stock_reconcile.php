<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetStockReconcile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loss_stock_reconcile', function (Blueprint $table) {
            $table->string('loss_type', 10)->default('loss');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loss_stock_reconcile', function (Blueprint $table) {
            $table->dropColumn('loss_type');
        });
    }
}
