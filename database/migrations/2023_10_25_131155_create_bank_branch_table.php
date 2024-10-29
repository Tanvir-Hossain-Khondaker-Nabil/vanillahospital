<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bank_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch_name');
            $table->unsignedInteger('bank_id');
            $table->string('status', 10)->default('active');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::statement(
"INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
        VALUES
        (NULL, 'member.bank_branch.index', 'List/All', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'bank_branch'),
        (NULL, 'member.bank_branch.create', 'Create', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'bank_branch'),
        (NULL, 'member.bank_branch.edit', 'Edit', NULL, 'active', '2023-08-16 10:04:47', '2023-08-16 10:04:47', 'bank_branch'); ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_branches');
    }
}
