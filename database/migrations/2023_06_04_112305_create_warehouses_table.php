<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->string('shortcode', 20);
            $table->string('mobile', 30);
            $table->string('address', 300);
            $table->string('longitude', 30)->nullable();
            $table->string('latitude', 30)->nullable();
            $table->string('contact_person', 50)->nullable();
            $table->string('nid', 50)->nullable();
            $table->string('featured_image', 50)->nullable();
            $table->string('gallery_images', 500)->nullable();
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('thana_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->boolean('active_status')->default('1');
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
        Schema::dropIfExists('warehouses');
    }
}
