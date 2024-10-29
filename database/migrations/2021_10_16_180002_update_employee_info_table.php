<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmployeeInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_info', function (Blueprint $table) {

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->date('dob')->nullable();
            $table->string('cv')->nullable();
            $table->string('document')->nullable();
            $table->string('nid')->nullable();
            $table->string('phone2')->nullable();


            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->unsignedSmallInteger('division_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('upazilla_id')->nullable();
            $table->unsignedInteger('union_id')->nullable();

            $table->foreign('division_id')->references('id')->on('divisions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('upazilla_id')->references('id')->on('upazillas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')
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
        Schema::table('employee_info', function (Blueprint $table) {
            $table->dropForeign('employee_info_user_id_foreign');
            $table->dropColumn('user_id');

            $table->dropColumn('address');
            $table->dropColumn('address2');
            $table->dropColumn('dob');
            $table->dropColumn('cv');
            $table->dropColumn('document');
            $table->dropColumn('nid');
            $table->dropColumn('phone2');

            $table->dropForeign('employee_info_division_id_foreign');
            $table->dropForeign('employee_info_district_id_foreign');
            $table->dropForeign('employee_info_upazilla_id_foreign');
            $table->dropForeign('employee_info_union_id_foreign');
            $table->dropColumn('division_id');
            $table->dropColumn('district_id');
            $table->dropColumn('upazilla_id');
            $table->dropColumn('union_id');
            $table->dropColumn('area_id');
        });
    }
}
