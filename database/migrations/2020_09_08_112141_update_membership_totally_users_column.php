<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMembershipTotallyUsersColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {

            $table->integer('total_users')->nullable()->comment('Total number of user can use');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {

            $table->dropColumn('total_users');

        });
    }
}
