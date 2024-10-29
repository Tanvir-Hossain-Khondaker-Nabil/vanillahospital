<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 1000);
            $table->unsignedInteger('lead_category_id');
            $table->unsignedInteger('lead_company_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();

            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();


           $table->foreign('client_id')->references('id')->on('clients')
               ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('lead_category_id')->references('id')->on('lead_categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('lead_company_id')->references('id')->on('quotationers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::dropIfExists('leads');
    }
}