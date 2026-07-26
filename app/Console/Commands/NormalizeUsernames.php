<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UsernameGenerator;
use Illuminate\Console\Command;

class NormalizeUsernames extends Command
{
    protected $signature = 'users:normalize-usernames';

    protected $description = 'Generate missing or template-tainted usernames for all users';

    public function handle(UsernameGenerator $generator): int
    {
        $users = User::where(function ($q) {
            $q->whereNull('username')
              ->orWhere('username', '')
              ->orWhere('username', 'like', '%{{%');
        })->get();

        if ($users->isEmpty()) {
            $this->info('All usernames look clean.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $user->username = $generator->generate($user->name, $user->getOriginal('username'));
            $user->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Normalized {$users->count()} username(s).");

        return self::SUCCESS;
    }
}
