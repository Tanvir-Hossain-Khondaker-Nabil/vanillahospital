<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentTypePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedInteger('payment_method_id')->nullable();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')
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
        Schema::table('purchases', function (Blueprint $table) {

            $table->dropForeign('purchases_payment_method_id_foreign');
            $table->dropColumn('payment_method_id');
        });
    }
}
