<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpdPatientInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipd_patient_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("company_id");
            $table->string("patient_info_id");
            $table->integer("reg_id");
            $table->string("patient_name");
            $table->string("age");
            $table->string("patient_image")->nullable();
            $table->string("email",40)->nullable();
            $table->string("password")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->integer("gender")->comment('1=Male,2=Female,3=other');
            $table->double("height")->nullable();
            $table->double("weight")->nullable();
            $table->string("doc_name",50)->nullable();
            $table->unsignedBigInteger("doctor_id");
            $table->string("ref_doc_name",50)->nullable();
            $table->unsignedBigInteger("ref_doctor_id");
            $table->text("address")->nullable();
            $table->string("phone",20)->nullable();
            $table->string("blood_group",10)->nullable();
            $table->unsignedBigInteger("cabin_no")->nullable();
            $table->integer("type")->comment('1=registration 2=cabin trnasfer 3=release 4=operation');
            $table->double("total_amount")->nullable();
            $table->double("paind_amount")->nullable();
            $table->integer("status")->default(1);
            $table->double("total_discount")->default(0);
            $table->double("total_vat")->default(0);
            $table->unsignedBigInteger("hospital_id")->nullable();
            $table->string("guardian_name",40)->nullable();
            $table->string("parents_name",40)->nullable();
            $table->text("parents_address")->nullable();
            $table->text("guardian_address")->nullable();
            $table->string("guardian_phone",20)->nullable();
            $table->string("parents_phone",20)->nullable();
            $table->string("disease_name")->nullable();
            $table->double("admission_fee")->default(0);
            $table->double("advance_payment")->default(0);
            $table->double("admission_fee_paid")->default(0);
            $table->string("blood_pressure")->nullable();
            $table->string("pulse_rate")->nullable();
            $table->text("description")->nullable();
            $table->timestamp("released_date")->nullable();
            $table->timestamp("admit_date_time")->nullable();
            $table->string("operator_name",40)->nullable();
            $table->unsignedBigInteger("operator_id")->nullable();
            $table->unsignedBigInteger("marketing_officer_id")->nullable();
            $table->integer("mem_reg_id")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ipd_patient_info');
    }
}
