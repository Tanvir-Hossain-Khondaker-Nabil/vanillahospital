<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class ModifyQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('quotations', 'others_transaction'))
        {
             Schema::table('quotations', function (Blueprint $table) {
                 $table->string('others_transaction')->nullable();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('quotations', 'others_transaction'))
        {
            Schema::table('quotations', function (Blueprint $table) {
                 $table->dropColumn('others_transaction');
            });
        }
    }
}
