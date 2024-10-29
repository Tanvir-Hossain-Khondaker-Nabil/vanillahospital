<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', '100')->nullable();
            $table->double('price',10,2)->nullable();
            $table->double('comission',10,2)->nullable();
            $table->string('status')->default('active')->nullable();

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
                        (NULL, 'member.hospital_service.index', 'List/All', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'),
                        (NULL, 'member.hospital_service.create', 'Create', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'),
                        (NULL, 'member.hospital_service.show', 'Show', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'),
                        (NULL, 'member.hospital_service.edit', 'Edit', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'),
                        (NULL, 'member.hospital_service.approved', 'Approved', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'),
                        (NULL, 'member.hospital_service.destroy', 'Delete', NULL, 'active', '2024-05-16 03:04:47', '2024-05-16 03:04:47', 'hospital_service'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_services');
    }
}