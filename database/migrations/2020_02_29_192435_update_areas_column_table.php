<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAreasColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('bn_name', 50)->nullable();
            $table->unsignedInteger('union_id')->nullable();
            $table->unsignedInteger('upazilla_id')->nullable();
            $table->string('url', 50)->nullable();

//            $table->foreign('union_id')->references('id')->on('unions')
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->foreign('upazilla_id')->references('id')->on('upazillas')
//                ->onUpdate('cascade')->onDelete('cascade');
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

//            $table->dropForeign('areas_union_id_foreign');
            $table->dropColumn('union_id');
//            $table->dropForeign('areas_upazilla_id_foreign');
            $table->dropColumn('upazilla_id');
        });
    }
}
