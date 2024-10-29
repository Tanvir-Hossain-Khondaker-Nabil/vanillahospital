<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_consultations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date')->nullable();
            $table->string('duration')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('patient_email')->nullable();
            $table->string('patient_phone_one')->nullable();
            $table->string('patient_phone_two')->nullable();
            $table->string('patient_address')->nullable();
            $table->string('gender')->nullable();
            $table->string('age')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('blood_group')->nullable();            
            $table->unsignedInteger('doctor_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('ipd_patient_info_registration_id')->nullable();
            $table->unsignedInteger('outdoor_registration_id')->nullable();
            $table->foreign('ipd_patient_info_registration_id')->references('id')->on('ipd_patient_info')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('outdoor_registration_id')->references('id')->on('out_door_registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            // \Illuminate\Support\Facades\DB::statement(
            //     "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
            //             VALUES
            //             (NULL, 'member.live_consultation.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'live_consultations'),
            //             (NULL, 'member.live_consultation.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'live_consultations'),
            //             (NULL, 'member.live_consultation.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'live_consultations'),
            //             (NULL, 'member.live_consultation.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'live_consultations'),
            //             (NULL, 'member.live_consultation.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'live_consultations'); ");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_consultations');
    }
}
