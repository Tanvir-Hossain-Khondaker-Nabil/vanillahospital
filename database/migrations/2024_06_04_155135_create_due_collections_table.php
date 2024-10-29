<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDueCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('due_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("company_id");
            $table->string('order_id')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->double('total_amount')->default(0);
            $table->double('discount')->default(0);
            $table->double('vat')->default(0);
            $table->double('current_due')->default(0);
            $table->double('paid_due')->default(0);
            $table->double('advance_payment')->default(0);
            $table->double('admission_fee')->default(0);
            $table->double('admission_fee_paid')->default(0);
            $table->double('admission_fee_discount')->default(0);
            $table->string('discount_ref')->nullable();
            $table->integer('due_type')->default(0)->comment('1=opd, 2=ipd 3=ipd outdoor service');
            $table->double('old_due')->default(0);
            $table->integer('status')->default(0)->comment('1=active 2=delete');
            $table->string('operator_name')->nullable();
            $table->integer('operator_id')->default(0);
            $table->double('service_charge')->default(0);
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
        Schema::dropIfExists('due_collections');
    }
}
