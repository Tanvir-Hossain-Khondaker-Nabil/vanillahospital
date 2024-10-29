<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueInAccountTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_types', function (Blueprint $table) {
            $table->unique(['display_name', 'company_id','member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropUnique('account_types_display_name_company_id_member_id_unique');
        });
    }
}
