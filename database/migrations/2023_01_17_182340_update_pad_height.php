<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePadHeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->unsignedInteger("pad_header_height")->default(50);
            $table->unsignedInteger("pad_footer_height")->default(50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropColumn("pad_header_height");
            $table->dropColumn("pad_footer_height");
        });
    }
}
