<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_code',12)->unique();
            $table->string('api_access_key', 40)->unique();
            $table->enum('status', ['active','inactive', 'block'])->default('active');
            $table->smallInteger('membership_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->dateTime('expire_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('memberships')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')
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
        Schema::dropIfExists('members');
    }
}
