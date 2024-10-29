<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDueCollectionHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('due_collection_histories', function (Blueprint $table) {
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('due_collection_histories', function (Blueprint $table) {
            $table->dropForeign('due_collection_histories_created_by_foreign');
            $table->dropForeign('due_collection_histories_updated_by_foreign');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
