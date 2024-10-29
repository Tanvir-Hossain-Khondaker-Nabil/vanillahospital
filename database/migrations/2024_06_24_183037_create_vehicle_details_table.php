<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number')->nullable();
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('vehicle_info_id');
            $table->unsignedInteger('vehicle_schedule_id');
            $table->unsignedInteger('ipd_patient_info_registration_id');
            $table->unsignedInteger('outdoor_registration_id');
            $table->string('patient_name')->nullable();
            $table->string('patient_email')->nullable();
            $table->string('patient_phone_one')->nullable();
            $table->string('patient_phone_two')->nullable();
            $table->string('patient_address')->nullable();
            $table->string('gender')->nullable();
            $table->string('age')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('start_date_and_time')->nullable();
            $table->tinyInteger('vehicle_status')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vehicle_info_id')->references('id')->on('vehicle_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vehicle_schedule_id')->references('id')->on('vehicle_schedules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ipd_patient_info_registration_id')->references('id')->on('ipd_patient_info')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('outdoor_registration_id')->references('id')->on('out_door_registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            // \Illuminate\Support\Facades\DB::statement(
            //     "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
            //             VALUES
            //             (NULL, 'member.vehicle_detail.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'member.vehicle_detail.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'member.vehicle_detail.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'member.vehicle_detail.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'reserve_vehicle', 'Reserve', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'booking_vehicle', 'Booking', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'),
            //             (NULL, 'member.vehicle_detail.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_details'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_details');
    }
}
