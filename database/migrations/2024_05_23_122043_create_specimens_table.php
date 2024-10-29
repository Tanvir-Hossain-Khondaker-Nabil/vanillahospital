<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecimensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specimens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('specimen')->nullable();
            $table->string('status')->default('active');

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
                        (NULL, 'member.specimen.index', 'List/All', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'),
                        (NULL, 'member.specimen.create', 'Create', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'),
                        (NULL, 'member.specimen.show', 'Show', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'),
                        (NULL, 'member.specimen.edit', 'Edit', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'),
                        (NULL, 'member.specimen.approved', 'Approved', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'),
                        (NULL, 'member.specimen.destroy', 'Delete', NULL, 'active', '2024-05-23 12:04:47', '2024-05-23 12:04:47', 'specimen'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specimens');
    }
}