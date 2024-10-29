<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(0)->comment('0=>Pending;1=>Approved;2=>Not Approved');
            $table->integer('year');
            $table->integer('month');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('users')
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
        Schema::dropIfExists('procurements');
    }
}
