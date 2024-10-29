<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name',70)->nullable();
            $table->string('email', 50)->unique();
            $table->string('phone', 30)->unique()->nullable();
            $table->string('verify_token', 40)->nullable();
            $table->smallInteger('membership_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('membership_id')->references('id')->on('memberships')
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
        Schema::dropIfExists('users');
    }
}
