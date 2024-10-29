<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectIdTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('project_expense_id')->nullable();
        });

        \Illuminate\Support\Facades\DB::statement("INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.report.project_expense_report', 'Project Expense Report', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses'),(NULL, 'member.report.project_profit_report', 'Project Profit Report', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project');");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('project_expense_id');
        });
    }
}
