<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchaseDetailsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `purchase_details` CHANGE `available_stock` `available_stock` INT(11) NULL DEFAULT '0', CHANGE `last_purchase_qty` `last_purchase_qty` INT(11) NULL DEFAULT '0'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
