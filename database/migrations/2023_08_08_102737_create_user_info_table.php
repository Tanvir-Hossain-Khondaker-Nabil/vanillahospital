<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `userinfo` (
                     `USERID` int(11) DEFAULT NULL,
                     `Badgenumber` varchar(24) DEFAULT NULL,
                     `SSN` varchar(20) DEFAULT NULL,
                     `Name` varchar(24) DEFAULT NULL,
                     `Gender` varchar(8) DEFAULT NULL,
                     `TITLE` varchar(20) DEFAULT NULL,
                     `PAGER` varchar(20) DEFAULT NULL,
                     `BIRTHDAY` datetime DEFAULT NULL,
                     `HIREDDAY` datetime DEFAULT NULL,
                     `street` varchar(80) DEFAULT NULL,
                     `CITY` varchar(2) DEFAULT NULL,
                     `STATE` varchar(2) DEFAULT NULL,
                     `ZIP` varchar(12) DEFAULT NULL,
                     `OPHONE` varchar(20) DEFAULT NULL,
                     `FPHONE` varchar(20) DEFAULT NULL,
                     `VERIFICATIONMETHOD` smallint(6) DEFAULT NULL,
                     `DEFAULTDEPTID` smallint(6) DEFAULT NULL,
                     `SECURITYFLAGS` smallint(6) DEFAULT NULL,
                     `ATT` smallint(6) DEFAULT NULL,
                     `INLATE` smallint(6) DEFAULT NULL,
                     `OUTEARLY` smallint(6) DEFAULT NULL,
                     `OVERTIME` smallint(6) DEFAULT NULL,
                     `SEP` smallint(6) DEFAULT NULL,
                     `HOLIDAY` smallint(6) DEFAULT NULL,
                     `MINZU` varchar(8) DEFAULT NULL,
                     `PASSWORD` varchar(20) DEFAULT NULL,
                     `LUNCHDURATION` smallint(6) DEFAULT NULL,
                     `MVERIFYPASS` varchar(10) DEFAULT NULL,
                     `PHOTO` mediumblob DEFAULT NULL,
                     `Notes` mediumblob DEFAULT NULL,
                     `privilege` int(11) DEFAULT NULL,
                     `InheritDeptSch` smallint(6) DEFAULT NULL,
                     `InheritDeptSchClass` smallint(6) DEFAULT NULL,
                     `AutoSchPlan` smallint(6) DEFAULT NULL,
                     `MinAutoSchInterval` int(11) DEFAULT NULL,
                     `RegisterOT` smallint(6) DEFAULT NULL,
                     `InheritDeptRule` smallint(6) DEFAULT NULL,
                     `EMPRIVILEGE` smallint(6) DEFAULT NULL,
                     `CardNo` varchar(20) DEFAULT NULL
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
        Schema::dropIfExists('userinfo');
    }
}
