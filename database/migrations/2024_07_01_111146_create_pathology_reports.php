<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathologyReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathology_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description',5000)->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('sub_test_group_id')->nullable();

            $table->foreign('sub_test_group_id')->references('id')->on('sub_test_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();


            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.pathology_reports.index', 'List/All', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'),
                        (NULL, 'member.pathology_reports.create', 'Create', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'),
                        (NULL, 'member.pathology_reports.show', 'Show', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'),
                        (NULL, 'member.pathology_reports.edit', 'Edit', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'),
                        (NULL, 'member.pathology_reports.approved', 'Approved', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'),
                        (NULL, 'member.pathology_reports.destroy', 'Delete', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'pathology_reports'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pathology_reports');
    }
}