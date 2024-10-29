<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOverflowReconcileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_overflow_reconcile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->double('qty');
            $table->date('enter_date');
            $table->integer('fiscal_year_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();

//            $table->unique( array('fiscal_year_id','item_id', 'company_id'),'stock_overflow_fiscal_year_item_company_id_unique' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_overflow_reconcile');
    }
}
