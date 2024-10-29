<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSuppliersOrCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->double('balance_limit')->nullable()->default(0);
            $table->double('sale_limit')->nullable()->default(0);
            $table->double('purchase_limit')->nullable()->default(0);
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
            $table->dropColumn('balance_limit');
            $table->dropColumn('sale_limit');
            $table->dropColumn('purchase_limit');
        });
    }
}
