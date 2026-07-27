<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reportable'); // reportable_type + reportable_id
            $table->string('reason'); // spam, harassment, hate_speech, misinformation, violence, other
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('notes')->nullable(); // Moderator notes
            $table->timestamps();

            // Index for admin dashboard queries
            $table->index(['status', 'created_at']);
            // Note: morphs() already adds an index on [reportable_type, reportable_id]
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
