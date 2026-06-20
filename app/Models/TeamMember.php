<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'description',
        'photo_path',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $member): void {
            ImageStorageNaming::syncStoredImage($member, 'photo_path', 'equipe_tecnica', $member->name);
        });
    }

    public function photoUrl(): ?string
    {
        if (! filled($this->photo_path)) {
            return null;
        }

        return $this->normalizeMediaUrl($this->photo_path);
    }

    protected function normalizeMediaUrl(string $path): string
    {
        $path = trim($path);

        if (preg_match('#^https?://#i', $path)) {
            $parsed = parse_url($path);

            if (is_array($parsed) && filled($parsed['path'] ?? null)) {
                $normalized = ltrim((string) $parsed['path'], '/');

                return str_starts_with($normalized, 'storage/')
                    ? '/' . $normalized
                    : '/storage/' . $normalized;
            }

            return $path;
        }

        $path = ltrim($path, '/');

        $path = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;

        return Storage::disk('public')->url($path);
    }
}
