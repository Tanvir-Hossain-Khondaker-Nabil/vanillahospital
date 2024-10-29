<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_id');
            $table->string('lead_status',20);
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
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
        Schema::dropIfExists('lead_statuses');
    }
}
