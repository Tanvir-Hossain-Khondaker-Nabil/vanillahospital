<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('venue');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('price');
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('vehicle_info_id');
            $table->tinyInteger('status')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vehicle_info_id')->references('id')->on('vehicle_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.vehicle_schedule.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_schedules'),
                        (NULL, 'member.vehicle_schedule.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_schedules'),
                        (NULL, 'member.vehicle_schedule.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_schedules'),
                        (NULL, 'member.vehicle_schedule.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_schedules'),
                        (NULL, 'member.vehicle_schedule.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'vehicle_schedules'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_schedules');
    }
}
