<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableDesignationModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {

            $table->unsignedInteger('company_id')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {

            $table->dropForeign('company_id');
            $table->dropColumn('company_id');
            $table->dropForeign('department_id');
            $table->dropColumn('department_id');
        });
    }
}
