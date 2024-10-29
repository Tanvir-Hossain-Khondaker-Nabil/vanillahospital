<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('leaves');

        $leave = "CREATE TABLE `leaves` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `title` varchar(255) NOT NULL,
                     `note` varchar(255) NOT NULL,
                     `status` tinyint(1) NOT NULL DEFAULT 1,
                     PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($leave);
        
        $emp_leave = "CREATE TABLE `emp_leave` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `emp_id` int(11) NOT NULL,
                         `leave_id` int(11) NOT NULL,
                         `start_date` date NOT NULL,
                         `end_date` date NOT NULL,
                         `l_note` varchar(400) NOT NULL,
                         `status` tinyint(1) NOT NULL DEFAULT 1,
                         PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($emp_leave);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('emp_leave');
    }
}
