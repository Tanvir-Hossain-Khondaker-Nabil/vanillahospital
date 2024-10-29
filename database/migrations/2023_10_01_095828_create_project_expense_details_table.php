<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectExpenseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `project_expenses` CHANGE `amount` `total_amount` DOUBLE NOT NULL;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `project_expenses` DROP FOREIGN KEY `project_expenses_project_expense_type_id_foreign`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `project_expenses` DROP INDEX `project_expenses_project_expense_type_id_foreign`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `project_expenses` DROP `project_expense_type_id`");


        Schema::create('project_expense_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_expense_id');
            $table->unsignedInteger('project_expense_type_id');
            $table->double('amount');
            $table->timestamps();

            $table->foreign('project_expense_id')->references('id')->on('project_expenses')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_expense_type_id')->references('id')->on('project_expense_types')
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
        Schema::dropIfExists('project_expense_details');
    }
}
