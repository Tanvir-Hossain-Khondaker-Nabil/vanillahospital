<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWarehouseStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_stock_reports', function (Blueprint $table) {

            $table->double('damage_qty')->default(0);
            $table->double('overflow_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_stock_reports', function (Blueprint $table) {
            $table->dropColumn('damage_qty');
            $table->dropColumn('overflow_qty');
        });
    }
}
