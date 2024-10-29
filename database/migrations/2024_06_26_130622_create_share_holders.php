<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareHolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_holders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('father_name');
             $table->string('mother_name');
             $table->string('nominee');
             $table->string('phone');
             $table->string('email');
             $table->string('address');
             $table->string('image');
             $table->string('passport_number');
             $table->string('nid_number');
             $table->string('signature_image');
             $table->string('type')->comment('1=management, 0=share holder');
             $table->string('share_number');
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.share_holders.index', 'List/All', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'),
                        (NULL, 'member.share_holders.create', 'Create', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'),
                        (NULL, 'member.share_holders.show', 'Show', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'),
                        (NULL, 'member.share_holders.edit', 'Edit', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'),
                        (NULL, 'member.share_holders.approved', 'Approved', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'),
                        (NULL, 'member.share_holders.destroy', 'Delete', NULL, 'active', '2024-06-01 12:04:47', '2024-06-01 12:04:47', 'share_holders'); ");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_holders');
    }
}