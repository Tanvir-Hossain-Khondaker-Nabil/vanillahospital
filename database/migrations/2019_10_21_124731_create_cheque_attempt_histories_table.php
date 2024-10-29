<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeAttemptHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_attempt_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cheque_entry_id');
            $table->date('last_attempt_date');
            $table->unsignedInteger('company_id');
            $table->timestamps();

            $table->foreign('cheque_entry_id')->references('id')->on('cheque_entries')
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
        Schema::dropIfExists('cheque_attempt_histories');
    }
}
