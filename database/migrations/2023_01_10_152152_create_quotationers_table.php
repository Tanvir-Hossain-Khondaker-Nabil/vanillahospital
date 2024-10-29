<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotationers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name',100);
            $table->string("designation", 100);
            $table->string('contact_no', 20)->nullable();
            $table->string('address_1', 100);
            $table->string('address_2', 100)->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedInteger('company_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('quotationers');
    }


}
