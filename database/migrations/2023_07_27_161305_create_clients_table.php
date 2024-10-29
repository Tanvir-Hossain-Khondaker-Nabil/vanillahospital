<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('phone_1')->nullable();
            $table->integer('phone_2')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->enum('status', ['active','inactive'])->nullable();
            $table->enum('owner_status', ['organization','person'])
                ->default('organization')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('label')->nullable();
            $table->string('client_image')->nullable();
            $table->string('card_image')->nullable();
            $table->unsignedSmallInteger('division_id');
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('quotationer_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('division_id')->references('id')->on('divisions')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('area_id')->references('id')->on('areas')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('district_id')->references('id')->on('districts')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('company_id')->references('id')->on('companies')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('updated_by')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('quotationer_id')->references('id')->on('quotationers')
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
        Schema::dropIfExists('clients');
    }
}
