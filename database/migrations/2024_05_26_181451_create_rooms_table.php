<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('cabin_class_id');
            $table->unsignedBigInteger('cabin_sub_class_id');
            $table->string('title');
            $table->integer('price');
            $table->integer('seat_capacity');
            $table->integer('is_busy')->comment('1=busy,0=free');
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.room.index', 'List/All', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'),
                        (NULL, 'member.room.create', 'Create', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'),
                        (NULL, 'member.room.show', 'Show', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'),
                        (NULL, 'member.room.edit', 'Edit', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'),
                        (NULL, 'member.room.approved', 'Approved', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'),
                        (NULL, 'member.room.destroy', 'Delete', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'room'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
