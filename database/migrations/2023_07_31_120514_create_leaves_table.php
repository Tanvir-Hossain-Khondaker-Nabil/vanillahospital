<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('employee_leaves');

        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('set_leave_id',10);
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('company_id');
            $table->date('leave_date');
            $table->string('note');
            $table->unsignedInteger('created_by');
            $table->unsignedTinyInteger('status')
                ->comment('0=Requested,1=Accepted,2=Canceled');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee_info')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
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
        Schema::dropIfExists('employee_leaves');
    }
}
