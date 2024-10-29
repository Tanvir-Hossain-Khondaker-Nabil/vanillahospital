<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabinClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabin_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.cabin_class.index', 'List/All', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'),
                        (NULL, 'member.cabin_class.create', 'Create', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'),
                        (NULL, 'member.cabin_class.show', 'Show', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'),
                        (NULL, 'member.cabin_class.edit', 'Edit', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'),
                        (NULL, 'member.cabin_class.approved', 'Approved', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'),
                        (NULL, 'member.cabin_class.destroy', 'Delete', NULL, 'active', '2024-05-25 12:04:47', '2024-05-25 12:04:47', 'cabin_class'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cabin_classes');
    }
}
