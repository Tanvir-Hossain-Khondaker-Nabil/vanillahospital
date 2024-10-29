<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatingTrackAccountHeadsBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_account_head_balance', function (Blueprint $table) {
            $table->enum('flag', ['delete', 'add'])->default('add');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('track_account_head_balance', function (Blueprint $table) {
            $table->dropColumn('flag');
        });
    }
}
