<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('division_id');
            $table->string('name', 30);
            $table->string('bn_name', 50)->nullable();
            $table->string('url', 50)->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->timestamps();


//            $table->foreign('division_id')->references('id')->on('divisions')
//                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
