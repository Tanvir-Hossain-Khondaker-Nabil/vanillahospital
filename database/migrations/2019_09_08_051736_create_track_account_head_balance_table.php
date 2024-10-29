<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackAccountHeadBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_account_head_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_type_id');
            $table->double('previous_balance');
            $table->double('current_balance');
            $table->date('date');
            $table->unsignedInteger('company_id');
            $table->timestamps();


            $table->foreign('account_type_id')->references('id')->on('account_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_account_head_balance');
    }
}
