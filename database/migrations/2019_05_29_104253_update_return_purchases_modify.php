<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReturnPurchasesModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_purchases', function (Blueprint $table) {

            $table->double('return_qty');
            $table->double('return_price');
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_purchases', function (Blueprint $table) {

            $table->dropColumn('return_qty');
            $table->dropColumn('return_price');
            $table->dropColumn('description');
        });
    }
}
