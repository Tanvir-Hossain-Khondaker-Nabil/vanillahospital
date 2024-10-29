<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSharerAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->unsignedSmallInteger('division_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('upazilla_id')->nullable();
//            $table->unsignedSmallInteger('thana_id')->nullable();
            $table->unsignedInteger('union_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();

            $table->foreign('division_id')->references('id')->on('divisions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('upazilla_id')->references('id')->on('upazillas')
                ->onUpdate('cascade')->onDelete('cascade');
//            $table->foreign('thana_id')->references('id')->on('thanas')
//                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')
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
        Schema::table('suppliers_or_customers', function (Blueprint $table) {

            $table->dropForeign('suppliers_or_customers_division_id_foreign');
            $table->dropForeign('suppliers_or_customers_district_id_foreign');
            $table->dropForeign('suppliers_or_customers_upazilla_id_foreign');
//            $table->dropForeign('suppliers_or_customers_thana_id_foreign');
            $table->dropForeign('suppliers_or_customers_union_id_foreign');
            $table->dropForeign('suppliers_or_customers_area_id_foreign');
            $table->dropColumn('division_id');
            $table->dropColumn('district_id');
            $table->dropColumn('upazilla_id');
//            $table->dropColumn('thana_id');
            $table->dropColumn('union_id');
            $table->dropColumn('area_id');
        });
    }
}
