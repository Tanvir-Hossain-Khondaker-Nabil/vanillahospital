<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('requisition_id')->nullable();
            $table->double('amount');
            $table->tinyInteger('status')->default(0)
                ->comment('0=pending,1=paid');
            $table->string('paid_notes')->nullable();
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
        Schema::dropIfExists('paid_commissions');
    }
}
