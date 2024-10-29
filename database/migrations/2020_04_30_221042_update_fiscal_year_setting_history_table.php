<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFiscalYearSettingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_year_setting_histories', function (Blueprint $table) {

            $table->integer('fiscal_year_id')->unsigned()->after('company_id');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiscal_year_setting_histories', function (Blueprint $table) {

            $table->dropForeign('fiscal_year_setting_histories_fiscal_year_id_foreign');
            $table->dropColumn('fiscal_year_id');
        });
    }
}
