<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_features', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->boolean('accept_direct_saleman_requisition')->default(0);
            $table->boolean('accept_multiple_dealer')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_features');
    }
}
