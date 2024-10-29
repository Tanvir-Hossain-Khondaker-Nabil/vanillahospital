<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSharerFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->unsignedInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users')
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

            $table->dropForeign('suppliers_or_customers_manager_id_foreign');
            $table->dropColumn('manager_id');
        });
    }
}
