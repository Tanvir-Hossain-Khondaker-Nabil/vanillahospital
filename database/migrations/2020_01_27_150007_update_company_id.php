<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_head_day_wise_balance', function (Blueprint $table) {

            // $table->unsignedInteger('company_id')->nullable();
            // $table->foreign('company_id')->references('id')->on('companies')
            //     ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_head_day_wise_balance', function (Blueprint $table) {
            // $table->dropForeign('account_head_day_wise_balance_company_id_foreign');
            // $table->dropColumn('company_id');
        });
    }
}
