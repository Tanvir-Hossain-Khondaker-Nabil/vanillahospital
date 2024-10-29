<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTexpadCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string("pad_header_image", 200)->nullable();
            $table->string("pad_footer_image",200)->nullable();
            $table->string("pad_watermark_image", 200)->nullable();
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
            $table->dropColumn("pad_header_html");
            $table->dropColumn("pad_footer_html");
            $table->dropColumn("pad_watermark_html");
        });
    }
}
