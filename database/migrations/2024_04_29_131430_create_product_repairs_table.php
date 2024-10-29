<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_repairs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('entry_date');
            $table->string('token',30)->unique();
            $table->unsignedTinyInteger('repair_type')->comment('0=Pre-sale,1=Others');
            $table->string('order_id', 30)->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->string('product_name',300)->nullable();
            $table->unsignedInteger('product_category')->nullable();
            $table->string('status', '15')->nullable();
            $table->boolean('defect_identity_call')->comment('0=no,1=yes')->nullable();
            $table->string('defect_type_ids', '50')->nullable();
            $table->string('defect_description', '500')->nullable();
            $table->string('take_screenshot', '200')->nullable();
            $table->date('estimate_delivery_date')->nullable();
            $table->time('es_delivery_time')->nullable();
            $table->unsignedInteger('repair_by')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('account_type_id')->nullable();

            $table->double('total_item_price')->nullable();
            $table->double('total_service_price')->nullable();
            $table->double('sub_total')->nullable();
            $table->double('discount')->nullable();
            $table->double('amount_to_pay');
            $table->double('paid')->nullable();
            $table->double('due')->nullable();
            $table->double('tax')->nullable();
            $table->double('tax_percent')->nullable();

            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::dropIfExists('product_repairs');
    }
}
