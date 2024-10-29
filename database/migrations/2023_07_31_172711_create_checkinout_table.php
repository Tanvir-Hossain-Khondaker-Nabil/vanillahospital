<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckinoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `checkinout` (
                     `USERID` int(11) DEFAULT NULL,
                     `CHECKTIME` datetime DEFAULT NULL,
                     `CHECKTYPE` varchar(1) DEFAULT NULL,
                     `VERIFYCODE` int(11) DEFAULT NULL,
                     `SENSORID` varchar(5) DEFAULT NULL,
                     `WorkCode` int(11) DEFAULT NULL,
                     `sn` varchar(20) DEFAULT NULL,
                     `UserExtFmt` smallint(6) DEFAULT NULL,
                     `Badgenumber` varchar(255) DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";


        \Illuminate\Support\Facades\DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkinout');
    }
}
