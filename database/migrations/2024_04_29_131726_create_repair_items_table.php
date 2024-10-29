<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('repair_id');
            $table->unsignedInteger('item_id');
            $table->boolean('cover_by_warranty')->comment('0=No,1=Yes');
            $table->boolean('item_type')->comment('0=Item,1=Service');
            $table->double('qty')->nullable();
            $table->double('price');
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
        Schema::dropIfExists('repair_items');
    }
}
