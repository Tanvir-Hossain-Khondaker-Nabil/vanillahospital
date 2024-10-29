<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_infos', function (Blueprint $table) {
            $table->increments('id');
            // $table->foreignId('driver_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();            
            $table->string('model_no');
            $table->string('model_year');
            $table->string('chassis_no');
            $table->string('engine_no');
            $table->string('gate_pass_year');
            $table->string('vehicle_doc');
            $table->tinyInteger('status')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.vehicle_info.index', 'List/All', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'),
                        (NULL, 'member.vehicle_info.create', 'Create', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'),
                        (NULL, 'member.vehicle_info.show', 'Show', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'),
                        (NULL, 'member.vehicle_info.edit', 'Edit', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'),
                        (NULL, 'member.vehicle_info.approved', 'Approved', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'),
                        (NULL, 'member.vehicle_info.destroy', 'Delete', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'vehicle_infos'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_infos');
    }
}
