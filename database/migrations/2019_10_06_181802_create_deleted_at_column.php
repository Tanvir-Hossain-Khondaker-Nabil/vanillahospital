<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeletedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('memberships', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('account_types', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('gl_account_classes', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('delivery_types', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('journal_entry_details', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('units', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('areas', function (Blueprint $table) {
            $table->softDeletes();
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
            $table->dropSoftDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('gl_account_classes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('delivery_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('journal_entry_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('areas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
