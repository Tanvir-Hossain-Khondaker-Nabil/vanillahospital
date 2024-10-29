<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDueCollectionHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('due_collection_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_type',15)->comment('Purchase/Sale');
            $table->integer('sharer_id')->unsigned()->nullable()->comment('Supplier/Customer Id');
            $table->integer('inventory_type_id')->unsigned()->nullable()->comment('Sale/Purchase Id');  // For Purchase / Sale Id
            $table->double('amount');
            $table->date('collection_date');
            $table->unsignedInteger('company_id')->nullable();
            $table->timestamps();

            $table->foreign('sharer_id')->references('id')->on('suppliers_or_customers')
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
        Schema::dropIfExists('due_collection_histories');
    }
}
