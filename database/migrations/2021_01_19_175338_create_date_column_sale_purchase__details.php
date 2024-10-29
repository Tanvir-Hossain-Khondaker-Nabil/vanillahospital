<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDateColumnSalePurchaseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->date('date')->nullable()->default(null);
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->date('date')->nullable()->default(null);
        });


        DB::statement("UPDATE sales_details a JOIN sales b ON a.sale_id = b.id  SET a.date = b.date");
        DB::statement("UPDATE purchase_details a JOIN purchases b ON a.purchase_id = b.id  SET a.date = b.date");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
}
