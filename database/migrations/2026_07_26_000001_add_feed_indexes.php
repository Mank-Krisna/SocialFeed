<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('posts', 'posts_created_at_id_index', 'INDEX', '(`created_at`, `id`)');
        $this->addIndexIfMissing('messages', 'messages_conversation_id_created_at_index', 'INDEX', '(`conversation_id`, `created_at`)');
        $this->addIndexIfMissing('stories', 'stories_expires_at_index', 'INDEX', '(`expires_at`)');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('posts', 'posts_created_at_id_index');
        $this->dropIndexIfExists('messages', 'messages_conversation_id_created_at_index');
        $this->dropIndexIfExists('stories', 'stories_expires_at_index');
    }

    private function addIndexIfMissing(string $table, string $name, string $type, string $columns): void
    {
        if (!Schema::hasTable($table)) return;

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            $exists = DB::select("SHOW INDEXES FROM `{$table}` WHERE Key_name = ?", [$name]);
            if (empty($exists)) {
                DB::statement("ALTER TABLE `{$table}` ADD {$type} `{$name}` {$columns}");
            }
        } elseif ($driver === 'sqlite') {
            try {
                Schema::table($table, fn ($t) => $t->index(...$this->parseColumns($columns)));
            } catch (\Throwable $e) { /* ignore */ }
        }
    }

    private function dropIndexIfExists(string $table, string $name): void
    {
        if (!Schema::hasTable($table)) return;

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            $exists = DB::select("SHOW INDEXES FROM `{$table}` WHERE Key_name = ?", [$name]);
            if (!empty($exists)) {
                DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
            }
        } elseif ($driver === 'sqlite') {
            try {
                Schema::table($table, fn ($t) => $t->dropIndex($name));
            } catch (\Throwable $e) { /* ignore */ }
        }
    }

    private function parseColumns(string $columns): array
    {
        preg_match_all('/`(\w+)`/', $columns, $matches);
        return $matches[1];
    }
};
