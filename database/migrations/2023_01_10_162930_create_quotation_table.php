<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('quote_date');
            $table->unsignedInteger('quoting_id');
            $table->unsignedInteger('contact_quoting_id');
            $table->unsignedInteger('quotationer_id');
            $table->unsignedInteger('quote_attention_id')->nullable();
            $table->string('ref', 500);
            $table->string('subject', 500);
            $table->string('starting_text', 1500);
            $table->string('ending_text', 1500);
            $table->unsignedInteger('company_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('quotations');
    }
}
