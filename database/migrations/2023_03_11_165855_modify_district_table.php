<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->unsignedSmallInteger('region_id')->nullable();
            $table->boolean('active_status')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('region_id');
            $table->dropColumn('active_status');
        });
    }
}
