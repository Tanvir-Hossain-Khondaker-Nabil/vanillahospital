<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersOrCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers_or_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone', 50);
            $table->string('address')->nullable();
            $table->string('email', 50)->nullable();
            $table->double('supplier_initial_balance')->nullable();
            $table->double('supplier_current_balance')->nullable();
            $table->double('customer_initial_balance')->nullable();
            $table->double('customer_current_balance')->nullable();
            $table->integer('member_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->enum('customer_type',['supplier', 'customer', 'both'])->default('customer');
            $table->enum('status',['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::dropIfExists('suppliers_or_customers');
    }
}
