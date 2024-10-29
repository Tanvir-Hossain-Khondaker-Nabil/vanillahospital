<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesRequisitionDetailsUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_requisition_details', function (Blueprint $table) {
           
            $table->unsignedInteger('sales_details_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_requisition_details', function (Blueprint $table) {
            
            $table->dropColumn('sales_details_id');
        });
    }
}
