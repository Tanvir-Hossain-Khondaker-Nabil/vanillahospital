<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateCompaniesModifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `companies` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");
        DB::statement("ALTER TABLE `companies` CHANGE `phone` `phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");
    
        
        Schema::table('companies', function (Blueprint $table) {
            $table->string('number_of_employee', 10)->nullable();
            $table->string('company_type_id', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
