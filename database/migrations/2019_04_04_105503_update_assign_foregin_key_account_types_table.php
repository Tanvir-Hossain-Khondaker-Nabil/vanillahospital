<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAssignForeginKeyAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_types', function (Blueprint $table) {

            $table->unsignedInteger('parent_id')->after('name')->nullable();
            $table->foreign('parent_id')->references('id')->on('account_types')
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
        Schema::table('account_types', function (Blueprint $table) {

            $table->dropForeign('account_types_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
}
