<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PrepareDeploy extends Command
{
    protected $signature = 'cerape:prepare-deploy {--skip-storage-link : Do not create the public/storage symlink}';

    protected $description = 'Prepare and validate the application for deploy.';

    public function handle(): int
    {
        $this->info('Validating deploy prerequisites...');

        if (! extension_loaded('gd')) {
            $this->warn('GD extension is not installed. PDF generation will fall back to SVG or omit unsupported raster images.');
        } else {
            $this->info('GD extension detected.');
        }

        if (config('app.locale') !== 'pt_BR') {
            $this->warn('APP_LOCALE is not pt_BR. Filament and validation strings may not appear in Portuguese.');
        } else {
            $this->info('Application locale is pt_BR.');
        }

        $storageLink = public_path('storage');

        if ($this->option('skip-storage-link')) {
            $this->line('Skipping storage:link creation.');
        } elseif (is_link($storageLink) || file_exists($storageLink)) {
            $this->info('Storage symlink already exists.');
        } else {
            $this->line('Creating storage symlink...');
            Artisan::call('storage:link', [
                '--force' => true,
            ]);

            $this->line(trim(Artisan::output()));
        }

        $this->info('Suggested production checks:');
        $this->line('- php artisan migrate --force');
        $this->line('- php artisan config:cache');
        $this->line('- php artisan route:cache');
        $this->line('- php artisan view:cache');
        $this->line('- php artisan filament:cache-components');

        return self::SUCCESS;
    }
}
