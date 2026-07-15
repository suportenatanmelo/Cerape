<?php

namespace App\Jobs;

use App\Models\HeroSlide;
use App\Models\HeroSlideTrash;
use App\Support\ActivityLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClearHeroImagesJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public ?int $userId = null, public bool $makeBackup = true)
    {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $timestamp = now()->format('Ymd_His');
        $backupPath = null;
        $result = [
            'found' => 0,
            'updated' => 0,
            'errors' => [],
        ];

        try {
            $slides = HeroSlide::query()->get(['id', 'image_path', 'mobile_image_path', 'og_image_path']);
            $result['found'] = $slides->count();

            if ($this->makeBackup && $slides->isNotEmpty()) {
                $backupData = $slides->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'image_path' => $s->image_path,
                        'mobile_image_path' => $s->mobile_image_path,
                        'og_image_path' => $s->og_image_path,
                    ];
                })->toArray();

                $backupPath = 'backups/hero_slides_backup_' . $timestamp . '.json';
                Storage::disk('local')->put($backupPath, json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            if ($slides->isNotEmpty()) {
                foreach ($slides as $s) {
                    HeroSlideTrash::create([
                        'hero_slide_id' => $s->id,
                        'title' => $s->title ?? null,
                        'image_path' => $s->image_path,
                        'mobile_image_path' => $s->mobile_image_path,
                        'og_image_path' => $s->og_image_path,
                        'payload' => null,
                        'deleted_by' => $this->userId,
                        'deleted_at' => now(),
                    ]);
                }

                $updated = HeroSlide::query()->update([
                    'image_path' => null,
                    'mobile_image_path' => null,
                    'og_image_path' => null,
                ]);

                $result['updated'] = $updated;
            }

            $result['backup_path'] = $backupPath;

            app(ActivityLogger::class)->custom(
                'Hero Slides',
                'update',
                'Executou limpeza de imagens dos hero slides',
                null,
                ['make_backup' => $this->makeBackup],
                $result,
            );

            Log::info('ClearHeroImagesJob completed', ['user_id' => $this->userId, 'result' => $result]);
        } catch (\Throwable $e) {
            $result['errors'][] = $e->getMessage();

            app(ActivityLogger::class)->custom(
                'Hero Slides',
                'update',
                'Falha ao limpar imagens dos hero slides',
                null,
                ['make_backup' => $this->makeBackup],
                $result,
            );

            Log::error('ClearHeroImagesJob failed', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}