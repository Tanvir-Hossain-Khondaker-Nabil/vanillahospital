<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueInCashBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_or_bank_accounts', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->unique(['title', 'company_id', 'member_id']);
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_or_bank_accounts', function (Blueprint $table) {
            $table->dropUnique('cash_or_bank_accounts_title_company_id_member_id_unique');
            $table->dropUnique('cash_or_bank_accounts_name_unique');
            $table->dropColumn('name');
        });
    }
}
