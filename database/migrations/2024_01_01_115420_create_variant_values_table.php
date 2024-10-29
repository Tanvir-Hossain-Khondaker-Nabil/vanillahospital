<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('variant_id');
            $table->string('name', 20);
            $table->string('image', 290)->nullable();
            $table->unsignedTinyInteger('image_or_code')->default(0)->comment('1=Image,2=Code');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('company_id');
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
        Schema::dropIfExists('variant_values');
    }
}
