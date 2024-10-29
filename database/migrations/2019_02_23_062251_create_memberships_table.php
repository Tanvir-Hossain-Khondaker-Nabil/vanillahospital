<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
            $table->string('display_text', 100);
            $table->text('description');
            $table->double('price');
            $table->integer('discount')->default(0);
            $table->enum('discount_type', ['Percentage', 'Fixed', 'None'] )->default('None');
            $table->integer('time_period')->comment('Half year/Yearly count');
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('memberships');
    }
}
