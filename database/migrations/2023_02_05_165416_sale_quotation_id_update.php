<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SaleQuotationIdUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedInteger('quotation_id')->nullable();
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedInteger('quotation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('quotation_id');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('quotation_id');
        });
    }
}
