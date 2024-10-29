<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_supports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('message');
            $table->string('status')->default('unseen');
            $table->string('message_status')->default('ask');
            $table->integer('re_id')->nullable();
            $table->unsignedInteger('generated_id');
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('generated_id')->references('id')->on('users')
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
        Schema::dropIfExists('staff_supports');
    }
}