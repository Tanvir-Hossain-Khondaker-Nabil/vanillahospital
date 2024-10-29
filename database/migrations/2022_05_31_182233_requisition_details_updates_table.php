<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequisitionDetailsUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisition_details', function (Blueprint $table) {
          
            $table->unsignedInteger('purchase_details_id')->nullable();
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
           
            $table->dropColumn('purchase_details_id');
        });
    }
}
