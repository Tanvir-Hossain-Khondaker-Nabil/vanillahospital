<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlAccountClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_account_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', '100');
            $table->string('class_type', '20');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->integer('company_id')->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('company_id')->references('id')->on('companies')
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
        Schema::dropIfExists('gl_account_classes');
    }
}
