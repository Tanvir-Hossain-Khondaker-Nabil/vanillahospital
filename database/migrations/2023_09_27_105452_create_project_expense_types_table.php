<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectExpenseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_expense_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name');
            $table->string('name');
            $table->string('description',1000)->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');

            \Illuminate\Support\Facades\DB::statement("INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.project_expense_types.index', 'List/All', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expense_types'), (NULL, 'member.project_expense_types.create', 'Create', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expense_types'), (NULL, 'member.project_expense_types.show', 'Show', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expense_types'), (NULL, 'member.project_expense_types.edit', 'Edit', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expense_types'),(NULL, 'member.project_expense_types.destroy', 'Delete', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'project_expense_types');");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_expense_types');
    }
}
