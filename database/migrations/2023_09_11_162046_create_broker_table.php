<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrokerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `suppliers_or_customers` CHANGE `customer_type` `customer_type` ENUM('broker', 'supplier', 'customer', 'both') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'customer';");

        // \Illuminate\Support\Facades\DB::statement("INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.broker.create', 'Broker Create', NULL, 'active', '2023-08-16 10:04:50', '2023-08-16 10:04:50', 'sharer');");

        // \Illuminate\Support\Facades\DB::statement("INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `status`, `created_at`, `updated_at`, `group_name`) VALUES (NULL, 'member.sharer.broker_list', 'Broker List', NULL, 'active', '2023-08-16 10:04:50', '2023-08-16 10:04:50', 'sharer');");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppilers_or_customers', function (Blueprint $table) {
            //
        });
    }
}