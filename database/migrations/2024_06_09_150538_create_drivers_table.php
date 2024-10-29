<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_one');
            $table->string('phone_two');
            $table->string('driving_license_no');
            $table->string('address');
            $table->tinyInteger('status')->nullable();
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
                        (NULL, 'member.driver.index', 'List/All', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'drivers'),
                        (NULL, 'member.driver.create', 'Create', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'drivers'),
                        (NULL, 'member.driver.show', 'Show', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'drivers'),
                        (NULL, 'member.driver.edit', 'Edit', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'drivers'),
                        (NULL, 'member.driver.destroy', 'Delete', NULL, 'active', '2024-05-23 10:04:47', '2024-05-23 10:04:47', 'drivers'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
