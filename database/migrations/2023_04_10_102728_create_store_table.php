<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_name');
            $table->string('mobile_no', 20);
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->string('full_address', 300);
            $table->string('city', 20);
            $table->string('store_image', 250)->nullable();
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('thana_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('country_id')->default(18);
            $table->unsignedInteger('company_id');
            $table->boolean('approval_status')->default(0);
            $table->dateTime('approval_date')->nullable();
            $table->unsignedInteger('created_by');
            $table->boolean('active_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
