<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDetailsTableUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->double('pack_qty')->default(0);
            $table->double('free_qty')->default(0);
            $table->double('trade_price')->default(0);
            $table->double('carton')->default(0);
            $table->double('free')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_details', function (Blueprint $table) {

            $table->dropColumn('pack_qty');
            $table->dropColumn('free_qty');
            $table->dropColumn('trade_price');
            $table->dropColumn('carton');
            $table->dropColumn('free');
        });
    }
}
