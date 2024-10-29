<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueInSharerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->string('photo')->nullable();
            $table->unique(['name', 'phone', 'company_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers_or_customers', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropUnique('suppliers_or_customers_name_phone_company_id_member_id_unique');
        });
    }
}
