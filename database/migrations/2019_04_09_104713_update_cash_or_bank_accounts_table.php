<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCashOrBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_or_bank_accounts', function (Blueprint $table) {
            $table->integer('account_type_id')->unsigned()->nullable();
            $table->integer('bank_charge_account_id')->unsigned()->nullable();

            $table->foreign('account_type_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bank_charge_account_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');


//            $table->unique(array('company_id','member_id','account_type_id'));
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

//            $table->dropUnique('cash_or_bank_accounts_company_id_member_id_account_type_id_unique');
            $table->dropForeign('cash_or_bank_accounts_bank_charge_account_id_foreign');
            $table->dropForeign('cash_or_bank_accounts_account_type_id_foreign');
            $table->dropColumn('account_type_id');
            $table->dropColumn('bank_charge_account_id');

        });
    }
}
