<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_types', function (Blueprint $table) {

            $table->unsignedInteger('class_id')->nullable()->default(null);

            $table->foreign('class_id')->references('id')->on('gl_account_classes')
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

            $table->dropForeign('account_types_class_id_foreign');
            $table->dropColumn('class_id');
        });
    }
}
