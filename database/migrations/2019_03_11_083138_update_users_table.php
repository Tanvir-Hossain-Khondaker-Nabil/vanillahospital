<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status',['active', 'inactive', 'blocked'])->after('password')->default('active');
            $table->integer('created_by')->after('status')->unsigned()->nullable();
            $table->integer('updated_by')->after('status')->unsigned()->nullable();
            $table->integer('company_id')->after('updated_by')->unsigned()->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign('users_created_by_foreign');
            $table->dropForeign('users_updated_by_foreign');
            $table->dropForeign('users_company_id_foreign');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('company_id');
            $table->dropColumn('status');
        });
    }
}
