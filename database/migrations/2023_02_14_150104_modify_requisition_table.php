<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisition_details', function (Blueprint $table) {
            $table->date("req_date")->nullable();
        });
        Schema::table('sales_requisition_details', function (Blueprint $table) {
            $table->date("req_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_details', function (Blueprint $table) {
            $table->dropColumn("req_date");
        });
        Schema::table('sales_requisition_details', function (Blueprint $table) {
            $table->dropColumn("req_date");
        });
    }
}
