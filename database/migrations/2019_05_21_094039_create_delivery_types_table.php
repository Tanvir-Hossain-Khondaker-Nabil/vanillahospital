<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name', 50)->unique();
            $table->string('name', 30)->unique();
            $table->string('description');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
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
        Schema::dropIfExists('delivery_types');
    }
}
