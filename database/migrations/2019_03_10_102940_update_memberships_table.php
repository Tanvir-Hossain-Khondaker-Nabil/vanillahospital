<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->integer('created_by')->after('status')->unsigned()->nullable();
            $table->integer('updated_by')->after('status')->unsigned()->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::table('memberships', function (Blueprint $table) {

            $table->dropForeign('memberships_created_by_foreign');
            $table->dropForeign('memberships_updated_by_foreign');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
