<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTypeTable extends Migration
{
    /**
     * Run the migrations.k
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name');
            $table->string('name');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();

            $table->unique(['name', 'member_id', 'company_id']);
            $table->unique(['display_name', 'name', 'member_id', 'company_id']);

            $table->foreign('member_id')->references('id')->on('members')
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
        Schema::dropIfExists('account_types');
    }
}
