<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone', 50);
            $table->string('address')->nullable();
            $table->string('email', 50)->nullable();
            $table->enum('status',['active', 'inactive'])->default('active');

            $table->unsignedSmallInteger('division_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('upazilla_id')->nullable();
            $table->unsignedInteger('union_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();

            $table->integer('member_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('division_id')->references('id')->on('divisions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('upazilla_id')->references('id')->on('upazillas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::dropIfExists('customers');
    }
}
