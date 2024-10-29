<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnologistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technologists', function (Blueprint $table) {
            $table->increments('id');



            $table->string('checked_by_degree')->nullable();
            $table->string('prepared_by_degree')->nullable();
            $table->string('technologist_degree')->nullable();

            $table->string('checked_by_signature')->nullable();
            $table->string('prepared_by_signature')->nullable();
            $table->string('technologist_signature')->nullable();

            $table->tinyInteger('technologist_status')->nullable()->comment('1=doctor, 2=staff');
            $table->tinyInteger('checked_by_status')->nullable()->comment('1=doctor, 2=staff');
            $table->tinyInteger('prepared_status')->nullable()->comment('1=doctor, 2=staff');
            $table->string('status')->nullable()->comment('1=active, 0=inactive');
            
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('specimen_id')->nullable();
            $table->unsignedInteger('test_group_id')->nullable();

            $table->unsignedInteger('technologist_doctor_id')->nullable();
            $table->unsignedInteger('technologist_employeeInfo_id')->nullable();
            $table->foreign('technologist_doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('technologist_employeeInfo_id')->references('id')->on('employee_info')->onUpdate('cascade')->onDelete('cascade');


            $table->unsignedInteger('prepared_employeeinfo_id')->nullable();
            $table->unsignedInteger('prepared_doctor_id')->nullable();
            $table->foreign('prepared_doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('prepared_employeeinfo_id')->references('id')->on('employee_info')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('checked_by_employeeinfo_id')->nullable();
            $table->unsignedInteger('checked_by_doctor_id')->nullable();
            $table->foreign('checked_by_doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('checked_by_employeeinfo_id')->references('id')->on('employee_info')->onUpdate('cascade')->onDelete('cascade');



            $table->foreign('specimen_id')->references('id')->on('specimens')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('test_group_id')->references('id')->on('test_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.technologists.index', 'List/All', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'),
                        (NULL, 'member.technologists.create', 'Create', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'),
                        (NULL, 'member.technologists.show', 'Show', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'),
                        (NULL, 'member.technologists.edit', 'Edit', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'),
                        (NULL, 'member.technologists.approved', 'Approved', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'),
                        (NULL, 'member.technologists.destroy', 'Delete', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'technologists'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technologists');
    }
}