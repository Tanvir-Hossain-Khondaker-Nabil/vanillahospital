<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "	CREATE TABLE `advance_shift` (
                         `id` bigint(20) NOT NULL AUTO_INCREMENT,
                         `emp_id` int(11) NOT NULL,
                         `shift_id` int(11) NOT NULL,
                         `start_date` date NOT NULL,
                         `end_date` date NOT NULL,
                         `l_note` varchar(400) NOT NULL,
                         `status` tinyint(1) NOT NULL DEFAULT 1,
                         `shift_type` int(11) NOT NULL DEFAULT 0,
                         PRIMARY KEY (`id`)
                        )  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advance_shift');
    }
}
