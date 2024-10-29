<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `overtime` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `employee_id` varchar(15) NOT NULL,
                     `hours` double NOT NULL,
                     `rate` double NOT NULL,
                     `date_overtime` date NOT NULL,
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
        Schema::dropIfExists('overtime');
    }
}
