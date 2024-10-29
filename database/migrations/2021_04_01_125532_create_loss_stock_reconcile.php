<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLossStockReconcile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loss_stock_reconcile', function (Blueprint $table) {
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

//            $table->unique( array('fiscal_year_id','item_id', 'company_id') );

//            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loss_stock_reconcile');
    }
}
