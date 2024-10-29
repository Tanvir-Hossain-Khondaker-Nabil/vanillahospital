<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('discount_type', ['Fixed','Percentage'])->default('Fixed');
            $table->double('discount')->default(0);
            $table->double('total_amount')->default(0);
            $table->text('notation')->nullable();
            $table->string('file')->nullable();
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
            $table->dropColumn('discount_type');
            $table->dropColumn('discount');
            $table->dropColumn('total_amount');
            $table->dropColumn('notation');
            $table->dropColumn('file');
        });
    }
}
