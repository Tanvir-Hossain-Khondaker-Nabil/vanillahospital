<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateUsersSupportPin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('support_pin', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('support_pin');
        });
    }
}
