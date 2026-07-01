<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CountHeroSlideTrash extends Command
{
    protected $signature = 'frontend:count-trash';

    protected $description = 'Return count of hero slide trash records';

    public function handle(): int
    {
        $count = \App\Models\HeroSlideTrash::count();
        $this->info((string) $count);
        return 0;
    }
}
