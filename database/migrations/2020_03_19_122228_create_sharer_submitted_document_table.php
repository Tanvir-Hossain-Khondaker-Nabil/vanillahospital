<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharerSubmittedDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sharer_submitted_documents', function (Blueprint $table) {
            $table->unsignedInteger('sharer_id');
            $table->unsignedInteger('document_type_id');

            $table->unique(['sharer_id', 'document_type_id']);

            $table->foreign('sharer_id')->references('id')->on('suppliers_or_customers');
            $table->foreign('document_type_id')->references('id')->on('document_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sharer_submitted_documents', function (Blueprint $table){
           $table->dropForeign('document_type_id');
           $table->dropForeign('sharer_id');
           $table->dropUnique('sharer_submitted_documents_sharer_id_document_type_id_unique');
        });
        Schema::dropIfExists('sharer_collected_documents');
    }
}
