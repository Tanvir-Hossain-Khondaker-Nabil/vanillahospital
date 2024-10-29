<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteAttentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_attentions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('designation');
            $table->string('department');
            $table->string('contact', 20);
            $table->unsignedInteger('quotationer_id')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('quote_attentions');
    }
}
