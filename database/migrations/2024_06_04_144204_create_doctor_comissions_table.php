<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorComissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_comissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comission_type')->nullable();
            $table->string('amount')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('test_group_id')->nullable();
            $table->unsignedInteger('doctor_id')->nullable();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('test_group_id')->references('id')->on('test_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.doctor_comission.index', 'List/All', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'),
                        (NULL, 'member.doctor_comission.create', 'Create', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'),
                        (NULL, 'member.doctor_comission.show', 'Show', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'),
                        (NULL, 'member.doctor_comission.edit', 'Edit', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'),
                        (NULL, 'member.doctor_comission.approved', 'Approved', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'),
                        (NULL, 'member.doctor_comission.destroy', 'Delete', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'doctor_comission'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_comissions');
    }
}
