<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('fiscal_year_id')->after('member_id')->unsigned()->nullable();
            $table->integer('country_id')->after('address')->unsigned();
            $table->string('city')->after('address')->nullable();
            $table->string('logo', 100)->after('company_name')->nullable();

            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years')
                ->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('country_id')->references('id')->on('countries')
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
        Schema::table('companies', function (Blueprint $table) {

            $table->dropForeign('companies_fiscal_year_id_foreign');
            $table->dropForeign('companies_country_id_foreign');
            $table->dropColumn('fiscal_year_id');
            $table->dropColumn('country_id');
            $table->dropColumn('city');
            $table->dropColumn('logo');
        });
    }
}
