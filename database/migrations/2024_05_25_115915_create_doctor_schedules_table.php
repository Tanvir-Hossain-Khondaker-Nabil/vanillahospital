<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('time_per_patient')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('doctor_id')->nullable();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.doctor_schedule.index', 'List/All', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'),
                        (NULL, 'member.doctor_schedule.create', 'Create', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'),
                        (NULL, 'member.doctor_schedule.show', 'Show', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'),
                        (NULL, 'member.doctor_schedule.edit', 'Edit', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'),
                        (NULL, 'member.doctor_schedule.approved', 'Approved', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'),
                        (NULL, 'member.doctor_schedule.destroy', 'Delete', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'doctor_schedule'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_schedules');
    }
}