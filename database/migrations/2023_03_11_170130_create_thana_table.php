<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thanas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('division_id');
            $table->unsignedInteger('region_id');
            $table->unsignedInteger('district_id');
            $table->boolean('active_status')->default(1);
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
        Schema::dropIfExists('thanas');
    }
}
