<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_officers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('contact_no',20)->nullable();
            $table->string('address')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger("operator_id");
            $table->string('operator_name')->nullable();
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.marketing_officer.index', 'List/All', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'),
                        (NULL, 'member.marketing_officer.create', 'Create', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'),
                        (NULL, 'member.marketing_officer.show', 'Show', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'),
                        (NULL, 'member.marketing_officer.edit', 'Edit', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'),
                        (NULL, 'member.marketing_officer.approved', 'Approved', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'),
                        (NULL, 'member.marketing_officer.destroy', 'Delete', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_officers');
    }
}
