<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project');
            $table->integer('price');
            $table->string('address');
            $table->string('description',1200);
            $table->date('start_date');
            $table->date('expire_date');
            $table->float('long', 10, 7)->nullable();
            $table->float('lat', 10, 7)->nullable();
            $table->enum('status', ['active','inactive'])->nullable();
            $table->enum('progress_status', ['open','completed','hold','canceled'])
                    ->default('open')->nullable();
            $table->enum('project_status', ['client','international'])
                    ->default('client')->nullable();
            $table->unsignedInteger('project_category_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_category_id')->references('id')->on('project_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
