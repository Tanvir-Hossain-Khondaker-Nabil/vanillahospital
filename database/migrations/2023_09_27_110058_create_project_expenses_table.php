<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('code');
            $table->date('date');
            $table->unsignedInteger('transaction_id')->nullable();
            $table->unsignedInteger('project_id');
            $table->double('total_amount');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_expense_type_id')->references('id')->on('project_expense_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

            \Illuminate\Support\Facades\DB::statement("INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.project_expenses.index', 'List/All', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses'), (NULL, 'member.project_expenses.create', 'Create', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses'), (NULL, 'member.project_expenses.show', 'Show', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses'), (NULL, 'member.project_expenses.edit', 'Edit', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses'),(NULL, 'member.project_expenses.destroy', 'Delete', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expenses');");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_expenses');
    }
}
