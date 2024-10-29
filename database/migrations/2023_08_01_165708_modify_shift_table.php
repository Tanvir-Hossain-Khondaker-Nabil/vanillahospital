<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `shifts` CHANGE `shift_type` `shift_name` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL;");

        Schema::table('shifts', function (Blueprint $table) {
            $table->time('late');
            $table->tinyInteger('shift_type')->default(0)->comment("0=Day, 1=Night");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('late');
            $table->dropColumn('shift_type');
        });
    }
}
