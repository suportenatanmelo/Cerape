<?php

namespace App\Models;

use App\Support\PdfImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AcolhidoGaleria extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'acolhido_galerias';

    protected $attributes = [
        'imagens' => '[]',
        'ativo' => true,
    ];

    protected $fillable = [
        'acolhido_id',
        'titulo',
        'descricao',
        'imagens',
        'ativo',
    ];

    protected $casts = [
        'imagens' => 'array',
        'ativo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $galeria): void {
            $galeria->imagens ??= [];
        });
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 480, 360)
            ->optimize()
            ->nonQueued();
    }

    /**
     * @return array<int, string>
     */
    public function galleryUrls(): array
    {
        $mediaUrls = $this->getMedia('gallery')
            ->sortBy('order_column')
            ->map(fn (Media $media): string => $media->getUrl())
            ->values()
            ->all();

        if ($mediaUrls !== []) {
            return $mediaUrls;
        }

        return collect($this->imagens ?? [])
            ->filter(fn (mixed $path): bool => filled($path))
            ->map(fn (string $path): ?string => PdfImage::publicUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    public function galleryCount(): int
    {
        $mediaCount = $this->getMedia('gallery')->count();

        if ($mediaCount > 0) {
            return $mediaCount;
        }

        return count(array_filter($this->imagens ?? []));
    }

    /**
     * @return array<int, array{date:string, label:string, images:array<int, array{url:string, caption:string, added_at:string}>}>
     */
    public function galleryTimeline(): array
    {
        $mediaTimeline = $this->getMedia('gallery')
            ->sortByDesc('created_at')
            ->groupBy(function (Media $media): string {
                return optional($media->created_at)->toDateString() ?? now()->toDateString();
            })
            ->map(function (Collection $media, string $date): array {
                $carbon = Carbon::parse($date);

                return [
                    'date' => $date,
                    'label' => $carbon->translatedFormat('d \\d\\e F \\d\\e Y'),
                    'images' => $media
                        ->sortBy('order_column')
                        ->map(function (Media $item): array {
                            $addedAt = $item->created_at ?? now();

                            return [
                                'url' => $item->getUrl(),
                                'caption' => 'Adicionada em '.$addedAt->translatedFormat('d/m/Y \\a\\s H:i'),
                                'added_at' => $addedAt->toIso8601String(),
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        if ($mediaTimeline !== []) {
            return $mediaTimeline;
        }

        $legacyImages = collect($this->imagens ?? [])
            ->filter(fn (mixed $path): bool => filled($path))
            ->map(fn (string $path): ?string => PdfImage::publicUrl($path))
            ->filter()
            ->values()
            ->all();

        if ($legacyImages === []) {
            return [];
        }

        return [[
            'date' => optional($this->updated_at)->toDateString() ?? now()->toDateString(),
            'label' => optional($this->updated_at)->translatedFormat('d \\d\\e F \\d\\e Y') ?? now()->translatedFormat('d \\d\\e F \\d\\e Y'),
            'images' => collect($legacyImages)
                ->map(function (string $url): array {
                    $addedAt = $this->updated_at ?? now();

                    return [
                        'url' => $url,
                        'caption' => 'Adicionada em '.$addedAt->translatedFormat('d/m/Y \\a\\s H:i'),
                        'added_at' => $addedAt->toIso8601String(),
                    ];
                })
                ->values()
                ->all(),
        ]];
    }

    public function lastGalleryUpdateLabel(): ?string
    {
        $latestMediaDate = $this->getMedia('gallery')
            ->sortByDesc('created_at')
            ->first()
            ?->created_at;

        $date = $latestMediaDate ?? $this->updated_at;

        return $date?->translatedFormat('d/m/Y H:i');
    }

    public function galleryPeriodsCount(): int
    {
        return count($this->galleryTimeline());
    }
}
