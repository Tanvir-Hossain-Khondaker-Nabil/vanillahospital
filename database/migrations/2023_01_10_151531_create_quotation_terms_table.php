<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_tag', 100)->unique();
            $table->string('name', 3000);
            $table->unsignedInteger('term_id')->nullable();
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
        Schema::dropIfExists('quotation_terms');
    }
}
