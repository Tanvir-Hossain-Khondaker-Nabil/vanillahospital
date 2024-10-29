<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('thana_id')->nullable();
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('division_id');
            $table->dropColumn('region_id');
            $table->dropColumn('thana_id');
            $table->dropColumn('district_id');
        });
    }
}
