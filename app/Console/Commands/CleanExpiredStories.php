<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;

class CleanExpiredStories extends Command
{
    protected $signature = 'stories:clean';

    protected $description = 'Delete expired stories older than 24 hours';

    public function handle(): void
    {
        $deleted = Story::where('expires_at', '<', now())->delete();

        $this->info("Deleted {$deleted} expired stories.");
    }
}
