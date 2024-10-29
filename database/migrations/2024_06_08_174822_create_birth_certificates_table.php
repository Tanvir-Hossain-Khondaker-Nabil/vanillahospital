<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBirthCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('birth_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('newborn_id_no');
            $table->string('serial_no');
            $table->string('name');
            $table->string('sex');
            $table->string('date_and_time_of_birth');
            $table->string('mother_s_id_no');
            $table->string('mother_s_name');
            $table->string('mother_s_nationality');
            $table->string('mother_s_religion');
            $table->string('father_s_id_no');
            $table->string('father_s_name');
            $table->string('father_s_nationality');
            $table->string('father_s_religion');
            $table->string('present_address');
            $table->string('permanent_address');
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
                        (NULL, 'member.birth_certificate.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'birth_certificates'),
                        (NULL, 'member.birth_certificate.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'birth_certificates'),
                        (NULL, 'member.birth_certificate.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'birth_certificates'),
                        (NULL, 'member.birth_certificate.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'birth_certificates'),
                        (NULL, 'member.birth_certificate.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'birth_certificates'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('birth_certificates');
    }
}
