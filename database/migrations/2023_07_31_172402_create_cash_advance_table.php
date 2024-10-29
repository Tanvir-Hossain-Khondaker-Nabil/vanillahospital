<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashAdvanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE `cashadvance` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `date_advance` date NOT NULL,
                     `employee_id` varchar(15) NOT NULL,
                     `amount` double NOT NULL,
                     PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


        \Illuminate\Support\Facades\DB::statement($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashadvance');
    }
}
