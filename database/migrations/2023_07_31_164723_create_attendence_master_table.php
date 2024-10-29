<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendenceMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $createStructure = "CREATE TABLE `attendence_master` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `employee_id` bigint(20) NOT NULL,
                             `company_id` bigint(20) NOT NULL,
                             `attend_date` date DEFAULT NULL,
                             `in_time` time DEFAULT NULL,
                             `in_time_extra` time DEFAULT NULL,
                             `out_time` time DEFAULT NULL,
                             `out_time_extra` time DEFAULT NULL,
                             `over_time` double DEFAULT 0,
                             `lateness` varchar(10) DEFAULT NULL,
                             `attend_status` varchar(250) DEFAULT NULL,
                             `attend_status_extra` varchar(250) DEFAULT NULL,
                             `shift` varchar(250) DEFAULT NULL,
                             `shift_advance` varchar(255) DEFAULT NULL,
                             `adv_shift_id` int(11) NOT NULL DEFAULT 0,
                             `leave_status` int(11) DEFAULT 0,
                             `atmonth` varchar(250) DEFAULT NULL,
                             `atyear` int(11) DEFAULT 0,
                             `isLock` int(11) DEFAULT 0,
                             `extraOT` float DEFAULT 0,
                             `OriginalOT` float DEFAULT 0,
                             `CHECKTIME` datetime DEFAULT NULL,
                             `Badgenumber` varchar(250) DEFAULT NULL,
                             `out_time_night` time DEFAULT NULL,
                             `shift_type` int(11) NOT NULL DEFAULT 0,
                             `shift_type_adv` int(11) NOT NULL DEFAULT 0,
                             `late_status` varchar(255) DEFAULT NULL,
                             `late_time_set` varchar(255) DEFAULT NULL,
                             `late_time` varchar(255) DEFAULT NULL,
                             PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($createStructure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendence_master');
    }
}
