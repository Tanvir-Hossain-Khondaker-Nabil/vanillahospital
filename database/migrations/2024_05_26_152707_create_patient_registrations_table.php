<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("company_id");
            $table->string("patient_info_id");
            $table->string("age");
            $table->date("date_of_birth");
            $table->string("birth_place")->nullable();
            $table->string("blood_group",10);
            $table->string("patient_name",40)->nullable();
            $table->string("father_name",40)->nullable();
            $table->string("mother_name",40)->nullable();
            $table->integer("gender")->comment('1=Male,2=Female,3=other');
            $table->integer("married_status")->comment("1=married,2=unmarried,3=others");
            $table->integer("religion")->comment("1=muslim,2=hindu,3=Buddhist,4=christian,5=other");
            $table->string("nid",30)->nullable();
            $table->string("passport",30)->nullable();
            $table->string("nationality",40)->nullable();
            $table->string("citizenship",40)->nullable();
            $table->string("language",40)->nullable();
            $table->string("occupaton")->nullable();
            $table->string("spouse_name",50)->nullable();
            $table->string("spouse_phone",20)->nullable();
            $table->string("guardian_name",50)->nullable();
            $table->string("guardian_phone",20)->nullable();
            $table->string("guardian_relation")->nullable();
            $table->unsignedBigInteger("present_address_id")->nullable();
            $table->unsignedBigInteger("parmanent_address_id")->nullable();
            $table->integer("health_insurance")->comment('0=no,1=yes');
            $table->string("insurance_company")->nullable();
            $table->string("emer_name",40)->nullable();
            $table->string("emer_ralation",50)->nullable();
            $table->string("emer_mobile1",20)->nullable();
            $table->string("emer_mobile2",20)->nullable();
            $table->timestamp("emer_date_time")->nullable();
            $table->softDeletes();
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.patient_registration.index', 'List/All', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'),
                        (NULL, 'member.patient_registration.create', 'Create', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'),
                        (NULL, 'member.patient_registration.show', 'Show', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'),
                        (NULL, 'member.patient_registration.edit', 'Edit', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'),
                        (NULL, 'member.patient_registration.approved', 'Approved', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'),
                        (NULL, 'member.patient_registration.destroy', 'Delete', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'patient_registration'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_registrations');
    }
}
