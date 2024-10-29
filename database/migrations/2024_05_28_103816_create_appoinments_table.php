<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppoinmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appoinments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appointment_id')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('visit_time')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('schedule')->nullable();
            $table->integer('fee')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('net_amount')->nullable();
            $table->integer('serial_no')->nullable();
            $table->string('date')->nullable();
            $table->unsignedInteger('doctor_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('doctor_schedule_day_id')->nullable();
            $table->unsignedInteger('cash_or_bank_id')->nullable();

            $table->foreign('cash_or_bank_id')->references('id')->on('cash_or_bank_accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('doctor_schedule_day_id')->references('id')->on('doctor_schedule_days')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.appoinments.index', 'List/All', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'),
                        (NULL, 'member.appoinments.create', 'Create', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'),
                        (NULL, 'member.appoinments.show', 'Show', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'),
                        (NULL, 'member.appoinments.edit', 'Edit', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'),
                        (NULL, 'member.appoinments.approved', 'Approved', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'),
                        (NULL, 'member.appoinments.destroy', 'Delete', NULL, 'active', '2024-05-28 12:04:47', '2024-05-28 12:04:47', 'appoinments'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appoinments');
    }
}