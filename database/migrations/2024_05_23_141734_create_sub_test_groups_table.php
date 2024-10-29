<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubTestGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_test_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->double('price',10,2)->nullable();
            $table->double('quack_ref_com',10,2)->nullable();
            $table->string('ref_val')->nullable();
            $table->string('unit')->nullable();
            $table->string('room_no')->nullable();
            $table->string('status')->default('active');
            $table->unsignedInteger('test_group_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();

            $table->foreign('test_group_id')->references('id')->on('test_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.sub_test_group.index', 'List/All', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'),
                        (NULL, 'member.sub_test_group.create', 'Create', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'),
                        (NULL, 'member.sub_test_group.show', 'Show', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'),
                        (NULL, 'member.sub_test_group.edit', 'Edit', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'),
                        (NULL, 'member.sub_test_group.approved', 'Approved', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'),
                        (NULL, 'member.sub_test_group.destroy', 'Delete', NULL, 'active', '2024-05-23 02:04:47', '2024-05-23 02:04:47', 'sub_test_group'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_sub_test_groups');
    }
}