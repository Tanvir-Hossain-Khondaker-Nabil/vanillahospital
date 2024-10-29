<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleRequisiitionDealerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_requisitions', function (Blueprint $table) {

            $table->unsignedInteger('dealer_id')->after('is_sale')->nullable();
            $table->smallInteger('requisition_request_by')->default(0)->comment('dealer = 0, sale_man = 1');

            $table->foreign('dealer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_requisitions', function (Blueprint $table) {

            $table->dropForeign('dealer_id');
            $table->dropColumn('dealer_id');
            $table->dropColumn('requisition_request_by');
        });
    }
}
