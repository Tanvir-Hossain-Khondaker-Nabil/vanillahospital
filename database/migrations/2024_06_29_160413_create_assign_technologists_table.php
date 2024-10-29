<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignTechnologistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_technologists', function (Blueprint $table) {
            $table->increments('id');
           
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('technologist_id')->nullable();
            $table->unsignedInteger('sub_test_group_id')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('technologist_id')->references('id')->on('technologists')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_test_group_id')->references('id')->on('sub_test_groups')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('assign_technologists');
    }
}