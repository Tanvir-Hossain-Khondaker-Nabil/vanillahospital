<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->date('date');
            $table->double('total_amount');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('transaction_id')->nullable();
            $table->tinyInteger('accept_status')->default(0);
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();


            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.requisition_expenses.index', 'List/All', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'),
                        (NULL, 'member.requisition_expenses.create', 'Create', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'),
                        (NULL, 'member.requisition_expenses.show', 'Show', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'),
                        (NULL, 'member.requisition_expenses.edit', 'Edit', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'),
                        (NULL, 'member.requisition_expenses.approved', 'Approved', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'),
                        (NULL, 'member.requisition_expenses.destroy', 'Delete', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'requisition_expenses'); ");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition_expenses');
    }
}
