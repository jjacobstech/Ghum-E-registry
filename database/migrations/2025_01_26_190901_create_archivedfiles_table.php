<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('archived_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->references('id')->on('files')->nullable();
            $table->foreignId('archived_by')->references('id')->on('users')->nullable();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size');
            $table->string('file_type');
            $table->timestamp('archive_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivedfiles');
    }
};