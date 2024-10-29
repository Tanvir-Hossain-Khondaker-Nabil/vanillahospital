<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {

            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `projects` CHANGE `project_status` `project_status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'client' ");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `projects` CHANGE `description` `description` VARCHAR(1200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `projects` CHANGE `address` `address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `projects` CHANGE `expire_date` `expire_date` DATE NULL;");

            $table->unsignedInteger('working_days')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('broker_id')->nullable();
            $table->unsignedInteger('lead_id')->nullable();
            $table->float('commission',4,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropColumn('working_days');
            $table->dropColumn('country_id');
            $table->dropColumn('broker_id');
            $table->dropColumn('lead_id');
            $table->dropColumn('commission');
        });
    }
}
