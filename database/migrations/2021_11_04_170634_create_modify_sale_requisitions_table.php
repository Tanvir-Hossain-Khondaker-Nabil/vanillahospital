<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifySaleRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('sales_requisitions', function (Blueprint $table) {

            $table->unsignedInteger('customer_id')->after('dealer_id')->nullable();

//            $table->foreign('customer_id')->references('id')->on('customers');
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

            $table->dropColumn('customer_id')->after('is_sale')->nullable();
        });
    }
}
