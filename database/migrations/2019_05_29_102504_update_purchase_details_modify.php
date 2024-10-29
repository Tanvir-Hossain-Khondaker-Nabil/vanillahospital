<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchaseDetailsModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->integer('available_stock')->default(0);
            $table->integer('last_purchase_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('available_stock');
            $table->dropColumn('last_purchase_qty');
        });
    }
}
