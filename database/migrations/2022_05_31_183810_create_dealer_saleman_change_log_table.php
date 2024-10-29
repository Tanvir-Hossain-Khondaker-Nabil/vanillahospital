<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSalemanChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('dealer_saleman_change_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dealer_id');
            $table->unsignedInteger('saleman_id');
            $table->unsignedInteger('dealer_saleman_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('deleted_by');
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
        Schema::dropIfExists('dealer_saleman_change_logs');
    }
}
