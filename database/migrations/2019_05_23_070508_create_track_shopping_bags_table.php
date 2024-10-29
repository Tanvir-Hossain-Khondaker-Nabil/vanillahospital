<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackShoppingBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_shopping_bags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bag_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->integer('qty');
            $table->timestamps();


            $table->foreign('sale_id')->references('id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bag_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_shopping_bags');
    }
}
