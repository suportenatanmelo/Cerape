<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AcolhidoVideo extends Model
{
    protected $table = 'acolhido_videos';

    protected $fillable = [
        'acolhido_id',
        'titulo',
        'descricao',
        'youtube_url',
        'youtube_video_id',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ordem' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $video): void {
            $video->youtube_video_id = static::extractYoutubeId($video->youtube_url);
        });
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function youtubeEmbedUrl(): ?string
    {
        if (blank($this->youtube_video_id)) {
            return null;
        }

        return 'https://www.youtube.com/embed/'.$this->youtube_video_id;
    }

    public function youtubeThumbnailUrl(): ?string
    {
        if (blank($this->youtube_video_id)) {
            return null;
        }

        return 'https://img.youtube.com/vi/'.$this->youtube_video_id.'/hqdefault.jpg';
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $trimmedUrl = trim($url);

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $trimmedUrl, $matches)) {
            return $matches[1];
        }

        if (Str::length($trimmedUrl) === 11 && preg_match('~^[A-Za-z0-9_-]{11}$~', $trimmedUrl) === 1) {
            return $trimmedUrl;
        }

        return null;
    }
}
