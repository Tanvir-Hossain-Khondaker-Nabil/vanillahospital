<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countryCode', 3);
            $table->string('countryName', 50);
            $table->string('currencyCode', 4);
            $table->string('fipsCode', 4);
            $table->string('isoNumeric', 4);
            $table->string('north', 25);
            $table->string('south', 25);
            $table->string('east', 25);
            $table->string('west', 25);
            $table->string('capital', 50);
            $table->string('continentName', 15);
            $table->string('continent', 10);
            $table->string('languages');
            $table->string('isoAlpha3', 4);
            $table->integer('geonameId');
            $table->string('phonecode', 4)->nullable();
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
        Schema::dropIfExists('countries');
    }
}
