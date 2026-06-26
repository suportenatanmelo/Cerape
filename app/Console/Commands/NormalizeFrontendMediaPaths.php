<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class NormalizeFrontendMediaPaths extends Command
{
    protected $signature = 'frontend:normalize-media-paths
        {--apply : Persist the normalized paths}
        {--dry-run : Show what would change without saving}';

    protected $description = 'Normaliza caminhos antigos de imagens do frontend para o formato /storage/...';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply') && ! $this->option('dry-run');

        $this->info($apply ? 'Aplicando normalizacao dos caminhos do frontend.' : 'Executando dry-run da normalizacao dos caminhos do frontend.');

        $summary = [
            'hero_slides' => $this->normalize(HeroSlide::query()->get(), 'image_path', $apply),
            'team_members' => $this->normalize(TeamMember::query()->get(), 'photo_path', $apply),
            'blog_posts' => $this->normalize(BlogPost::query()->get(), 'image_path', $apply),
            'gallery_items' => $this->normalize(GalleryItem::query()->get(), 'image_path', $apply),
        ];

        foreach ($summary as $table => $result) {
            $this->line(sprintf(
                '%s: %d encontrados, %d alterados%s',
                $table,
                $result['found'],
                $result['updated'],
                $apply ? '' : ' (dry-run)'
            ));
        }

        return self::SUCCESS;
    }

    private function normalize(iterable $records, string $attribute, bool $apply): array
    {
        $found = 0;
        $updated = 0;

        foreach ($records as $record) {
            $found++;

            $current = trim((string) $record->getAttribute($attribute));
            $normalized = $this->normalizePath($current);

            if ($current === '' || $normalized === null || $normalized === $current) {
                continue;
            }

            $updated++;

            if ($apply) {
                $record->forceFill([$attribute => $normalized])->saveQuietly();
            }
        }

        return compact('found', 'updated');
    }

    private function normalizePath(string $path): ?string
    {
        $path = trim($path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['data:', '//'])) {
            return $path;
        }

        if (preg_match('#^https?://#i', $path)) {
            $parsed = parse_url($path);
            $path = ltrim((string) ($parsed['path'] ?? ''), '/');
        }

        $path = ltrim(Str::replaceFirst('storage/', '', $path), '/');

        if ($path === '') {
            return null;
        }

        return '/storage/' . $path;
    }
}
