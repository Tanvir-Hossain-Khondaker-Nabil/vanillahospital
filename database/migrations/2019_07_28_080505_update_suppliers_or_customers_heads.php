<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSuppliersOrCustomersHeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->unsignedInteger('account_type_id')->nullable();
            $table->unsignedInteger('cash_or_bank_id')->nullable();


            $table->foreign('account_type_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')
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
            $table->dropForeign('suppliers_or_customers_account_type_id_foreign');
            $table->dropForeign('suppliers_or_customers_cash_or_bank_id_foreign');
            $table->dropColumn('account_type_id');
            $table->dropColumn('cash_or_bank_id');
        });
    }
}
