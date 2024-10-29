<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('bn_name', 50)->nullable();
            $table->unsignedInteger('upazilla_id');
            $table->string('url', 50)->nullable();
            $table->timestamps();


            $table->foreign('upazilla_id')->references('id')->on('upazillas')
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
        Schema::dropIfExists('unions');
    }
}
