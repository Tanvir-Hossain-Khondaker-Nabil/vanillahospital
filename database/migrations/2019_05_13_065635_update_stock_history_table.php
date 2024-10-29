<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_histories', function (Blueprint $table) {

            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
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
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->dropForeign('stock_histories_company_id_foreign');
            $table->dropForeign('stock_histories_member_id_foreign');
            $table->dropColumn('company_id');
            $table->dropColumn('member_id');
        });
    }
}
