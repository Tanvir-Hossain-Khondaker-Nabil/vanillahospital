<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSupplierPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_purchases', function (Blueprint $table) {

            $table->unsignedInteger('company_id');

            $table->foreign('company_id')->references('id')->on('companies')
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
        Schema::table('supplier_purchases', function (Blueprint $table) {
            $table->dropForeign('supplier_purchases_company_id_foreign');
            $table->dropColumn('company_id');
        });
    }
}
