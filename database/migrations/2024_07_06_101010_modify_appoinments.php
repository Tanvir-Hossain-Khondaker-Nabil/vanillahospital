<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAppoinments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appoinments', function (Blueprint $table) {

            // $table->string('due')->nullable();
            // $table->string('paid_amount')->nullable();
            // $table->unsignedInteger('transaction_id')->nullable();

            // $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('account_type_id')->nullable();
            $table->foreign('account_type_id')->references('id')->on('account_types')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appoinments', function (Blueprint $table) {
            //
        });
    }
}
