<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWarehouseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_histories', function (Blueprint $table) {
            $table->string('code',20)->after('id');
            $table->unsignedInteger('item_id')->after('model_id');
            $table->unsignedInteger('company_id')->after('item_id');
            $table->date('date')->after('qty');


            $table->foreign('item_id')->references('id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
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
        Schema::table('warehouse_histories', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('item_id');
            $table->dropColumn('date');
        });
    }
}
