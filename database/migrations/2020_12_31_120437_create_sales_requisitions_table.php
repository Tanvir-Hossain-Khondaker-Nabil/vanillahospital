<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->double('total_price');
            $table->string('notation')->nullable();
            $table->unsignedTinyInteger('is_sale')->default(0);
            $table->integer('member_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();

//            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete(null);;
//            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete(null);;
//            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');;
//            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_requisitions');
    }
}
