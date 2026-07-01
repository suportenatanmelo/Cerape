<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use Illuminate\Console\Command;

class ClearHeroImages extends Command
{
    protected $signature = 'frontend:clear-hero-images {--dry-run : Show what would be cleared without saving}';

    protected $description = 'Remove image paths from all HeroSlide records so admin can re-upload new images.';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $count = HeroSlide::query()->count();

        if ($count === 0) {
            $this->info('Nenhum registro de HeroSlide encontrado.');
            return self::SUCCESS;
        }

        $this->line(sprintf('Encontrados %d slides.', $count));

        if ($dry) {
            $this->info('Dry-run: nenhuma alteração aplicada. Use o comando sem --dry-run para aplicar.');
            return self::SUCCESS;
        }

        $updated = HeroSlide::query()->update([
            'image_path' => null,
            'mobile_image_path' => null,
            'og_image_path' => null,
        ]);

        $this->info(sprintf('Atualizados %d slides (imagens removidas).', $updated));

        return self::SUCCESS;
    }
}
