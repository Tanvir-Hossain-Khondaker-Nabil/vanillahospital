<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStocksReportUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_reports', function (Blueprint $table) {
            $table->double('purchase_return_qty')->default(0);
            $table->double('sale_return_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_reports', function (Blueprint $table) {
            $table->dropColumn('purchase_return_qty');
            $table->dropColumn('sale_return_qty');
        });
    }
}
