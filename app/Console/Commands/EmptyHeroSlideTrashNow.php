<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmptyHeroSlideTrashNow extends Command
{
    protected $signature = 'frontend:empty-trash-now';

    protected $description = 'Empty hero slide trash now (runs as admin user 1)';

    public function handle(): int
    {
        $this->info('Emptying hero slide trash...');

        $user = \App\Models\User::find(1);
        if (! $user) {
            $this->error('Admin user id=1 not found. Aborting.');
            return 1;
        }

        auth()->setUser($user);

        (new \App\Http\Controllers\Admin\HeroSlideTrashController())->empty(request());

        $this->info('Done.');

        return 0;
    }
}
