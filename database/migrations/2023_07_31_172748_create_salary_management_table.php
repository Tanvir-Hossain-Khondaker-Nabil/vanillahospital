<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `salary_management` (
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `emp_id` int(11) NOT NULL,
                 `emp_name` varchar(100) NOT NULL,
                 `emp_designation` varchar(100) NOT NULL,
                 `en_month` varchar(40) NOT NULL,
                 `en_year` varchar(40) NOT NULL,
                 `total_present` int(11) NOT NULL DEFAULT 0,
                 `total_absent` int(11) NOT NULL DEFAULT 0,
                 `total_weekend` int(11) NOT NULL DEFAULT 0,
                 `holiday` int(11) NOT NULL DEFAULT 0,
                 `base_salary` double NOT NULL DEFAULT 0,
                 `absent_amount` double NOT NULL DEFAULT 0,
                 `over_time` double NOT NULL DEFAULT 0,
                 `over_time_amount` double NOT NULL DEFAULT 0,
                 `festival_bonus` double NOT NULL DEFAULT 0,
                 `bonus_percentage` double NOT NULL DEFAULT 0,
                 `net_payable` double NOT NULL DEFAULT 0,
                 `advance_payment` double NOT NULL DEFAULT 0,
                 `ov_ti_and_ab_cou` int(11) NOT NULL DEFAULT 0,
                 `total_work_day` double NOT NULL DEFAULT 0,
                 `total_att_amount` double NOT NULL DEFAULT 0,
                 `sign` tinyint(4) NOT NULL DEFAULT 0,
                 `work_day` int(11) NOT NULL DEFAULT 0,
                 `extra_work` int(11) DEFAULT 0,
                 `p_day` int(11) NOT NULL DEFAULT 0,
                 `p_day_amount` double NOT NULL DEFAULT 0,
                 PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_management');
    }
}
