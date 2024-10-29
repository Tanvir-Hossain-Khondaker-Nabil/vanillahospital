<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty',12,2);
            $table->decimal('amount',12,2);
            $table->integer('status')->default(0)->comment('0=>Pending;1=>Approved;2=>Not Approved');
            $table->unsignedInteger('procurement_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('company_id');

            $table->foreign('procurement_id')->references('id')->on('procurements')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
            ->onUpdate('cascade')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_details');
    }
}
