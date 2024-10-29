<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitBalanceTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_balance_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fiscal_year_id');
            $table->double('balance');
            $table->date('end_date');
            $table->date('start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profit_balance_tracks');
    }
}
