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
        Schema::create('files', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('file_name');
            $table->string('original_file_name');
            $table->bigInteger('file_size');
            $table->longText('file_path'); //link to cloud strage
            $table->string('file_type'); //.pdf,.doc or other file types
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('sender_id')->references('id')->on('users');
            $table->foreignId('receiver_id')->references('id')->on('users');
            $table->string('subject');
            $table->string('dept_in_request')->constrained('users', 'department');
            $table->string('assigned_to');
            $table->enum('status', ['action_required', 'pending', 'accepted', 'rejected'])->default('action_required');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};