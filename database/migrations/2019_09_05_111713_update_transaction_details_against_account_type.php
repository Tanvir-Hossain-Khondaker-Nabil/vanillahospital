<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionDetailsAgainstAccountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {

            $table->unsignedInteger('against_account_type_id')->nullable()->default(null);

            $table->foreign('against_account_type_id')->references('id')->on('account_types')
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
        Schema::table('transaction_details', function (Blueprint $table) {

            $table->dropForeign('transaction_details_against_account_type_id_foreign');
            $table->dropColumn('against_account_type_id');
        });
    }
}
