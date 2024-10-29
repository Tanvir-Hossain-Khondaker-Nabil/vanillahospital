<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReturnCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_return', function (Blueprint $table) {
            $table->dropUnique('sales_return_return_code_unique');
        });
        Schema::table('return_purchases', function (Blueprint $table) {
            $table->dropUnique('return_purchases_return_code_unique');
        });
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
