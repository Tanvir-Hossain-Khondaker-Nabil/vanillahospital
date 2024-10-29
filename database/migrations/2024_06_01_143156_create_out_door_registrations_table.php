<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutDoorRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_door_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('patient_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('ipd_patient')->nullable();
            $table->string('member_patient')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_service')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('age')->nullable();
            $table->string('total_doct_comission')->nullable();
            $table->string('net_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('total_sub_c_o')->nullable();
            $table->string('discount_percent')->nullable();
            $table->string('discount')->nullable();
            $table->string('discount_ref')->nullable();
            $table->string('total_paid')->nullable();
            $table->string('due')->nullable();

            $table->unsignedInteger('reg_patient_id')->nullable();
            $table->string('doctor_id')->nullable();
            $table->string('ref_doctor_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();


            $table->foreign('reg_patient_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');

            // $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('ref_doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.out_door_registration.index', 'List/All', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'),
                        (NULL, 'member.out_door_registration.create', 'Create', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'),
                        (NULL, 'member.out_door_registration.show', 'Show', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'),
                        (NULL, 'member.out_door_registration.edit', 'Edit', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'),
                        (NULL, 'member.out_door_registration.approved', 'Approved', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'),
                        (NULL, 'member.out_door_registration.destroy', 'Delete', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'out_door_registration'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('out_door_registrations');
    }
}