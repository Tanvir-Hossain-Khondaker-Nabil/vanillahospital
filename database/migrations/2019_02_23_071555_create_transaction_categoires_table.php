<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionCategoiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50)->unique();
            $table->string('display_name', 50);
            $table->enum('type', ['expense','income'])->default('expense');
            $table->softDeletes();
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
        Schema::dropIfExists('transaction_categories');
    }
}
