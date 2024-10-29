<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('requisition_id');
            $table->float('commission_percentage');
            $table->double('commission_amount');
            $table->double('total_sales_amount');
            $table->string('notes')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=paid');
            $table->string('paid_notes')->nullable();
            $table->date('generate_date');
            $table->time('generate_time');
            $table->date('paid_date')->nullable();
            $table->time('paid_time')->nullable();
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
        Schema::dropIfExists('sale_commissions');
    }
}
