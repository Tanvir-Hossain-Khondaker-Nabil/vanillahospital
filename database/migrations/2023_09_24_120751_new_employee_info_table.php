<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewEmployeeInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_info', function (Blueprint $table) {
            $table->string('bank_account', 20)->nullable();
            $table->string('other_bank_account', 20)->nullable();
            $table->string('bank_payment_type',10)->default('own')
                    ->comment('own, other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_info', function (Blueprint $table) {
            //
        });
    }
}
