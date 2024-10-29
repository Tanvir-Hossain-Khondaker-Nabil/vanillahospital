<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('status')->default('active');
            $table->unsignedInteger('specimen_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();

            $table->foreign('specimen_id')->references('id')->on('specimens')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.test_group.index', 'List/All', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'),
                        (NULL, 'member.test_group.create', 'Create', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'),
                        (NULL, 'member.test_group.show', 'Show', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'),
                        (NULL, 'member.test_group.edit', 'Edit', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'),
                        (NULL, 'member.test_group.approved', 'Approved', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'),
                        (NULL, 'member.test_group.destroy', 'Delete', NULL, 'active', '2024-05-23 01:04:47', '2024-05-23 01:04:47', 'test_group'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_groups');
    }
}
