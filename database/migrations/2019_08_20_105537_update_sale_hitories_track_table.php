<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSaleHitoriesTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->double('previous_stock');
            $table->double('current_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->dropColumn('previous_stock');
            $table->dropColumn('current_stock');
        });
    }
}
