<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->string('type')->default('like')->after('post_id');
            $table->index(['post_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'type']);
            $table->dropColumn('type');
        });
    }
};
