<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old tables
        Schema::dropIfExists('post_images');
        Schema::dropIfExists('app_notifications');

        // post_media — aligned with PRD (file_path, type)
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('type')->default('image'); // image | video
            $table->timestamps();
        });

        // notifications — aligned with PRD (data JSON)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // like, comment, friend_request, friend_accepted
            $table->json('data')->nullable(); // flexible JSON payload
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('post_media');
    }
};
