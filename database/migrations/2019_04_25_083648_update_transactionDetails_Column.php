<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionDetailsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {

            $table->unsignedInteger('account_class_id')->nullable()->default(null);

            $table->foreign('account_class_id')->references('id')->on('gl_account_classes')
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

            $table->dropForeign('transaction_details_account_class_id_foreign');
            $table->dropColumn('account_class_id');
        });
    }
}
