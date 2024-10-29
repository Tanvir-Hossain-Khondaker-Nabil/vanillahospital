<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->double('total_price');
            $table->string('notation')->nullable();
            $table->unsignedTinyInteger('is_purchased')->default(0);
            $table->unsignedInteger('member_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
