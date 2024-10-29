<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', '100')->nullable();
            $table->string('degree', '100')->nullable();
            $table->double('consult_fee',10,2)->nullable();
            $table->double('fee_old_patient',10,2)->nullable();
            $table->double('fee_only_report',20,2)->nullable();
            $table->double('emergency_fee',10,2)->nullable();
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->integer('mobile')->nullable();
            $table->string('status')->default('active');
            $table->integer('marketing_officer_id')->default(0);

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
                        (NULL, 'member.doctors.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'),
                        (NULL, 'member.doctors.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'),
                        (NULL, 'member.doctors.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'),
                        (NULL, 'member.doctors.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'),
                        (NULL, 'member.doctors.approved', 'Approved', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'),
                        (NULL, 'member.doctors.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'doctors'); ");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}