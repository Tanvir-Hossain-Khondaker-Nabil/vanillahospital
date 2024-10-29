<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealer_sales', function (Blueprint $table) {
           $table->dropForeign('dealer_sales_customer_id_foreign');
           $table->dropIndex('dealer_sales_customer_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_sales', function (Blueprint $table) {
            //
        });
    }
}
