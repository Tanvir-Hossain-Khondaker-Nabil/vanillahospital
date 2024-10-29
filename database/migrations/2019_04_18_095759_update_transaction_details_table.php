<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {

            $table->unsignedInteger('account_group_id')->nullable()->default(null);

            $table->foreign('account_group_id')->references('id')->on('account_types')
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

            $table->dropForeign('transaction_details_account_group_id_foreign');
            $table->dropColumn('account_group_id');
        });
    }
}
