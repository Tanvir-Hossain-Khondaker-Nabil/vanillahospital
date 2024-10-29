<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingOfficerCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_officer_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger("marketing_officer_id");
            $table->unsignedBigInteger("hospital_services_id");
            $table->integer('commission_type')->comment('0=taka,1=percentage');
            $table->double("commission_amount");
            $table->integer('status')->default(1);
            $table->unsignedBigInteger("operator_id");
            $table->string('operator_name')->nullable();
            $table->timestamps();
            \Illuminate\Support\Facades\DB::statement(
                "INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`)
                        VALUES
                        (NULL, 'member.marketing_officer_commissions.index', 'List/All', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'),
                        (NULL, 'member.marketing_officer_commissions.create', 'Create', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'),
                        (NULL, 'member.marketing_officer_commissions.show', 'Show', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'),
                        (NULL, 'member.marketing_officer_commissions.edit', 'Edit', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'),
                        (NULL, 'member.marketing_officer_commissions.approved', 'Approved', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'),
                        (NULL, 'member.marketing_officer_commissions.destroy', 'Delete', NULL, 'active', '2024-06-04 12:04:47', '2024-06-04 12:04:47', 'marketing_officer_commissions'); ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_officer_commissions');
    }
}
