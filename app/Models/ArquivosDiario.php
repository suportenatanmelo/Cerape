<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArquivosDiario extends Model
{
    public const CREATED_AT = null;

    protected $fillable = [
        'titulo',
        'upload_arquivo',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (self $arquivo): void {
            $arquivo->normalizeUploadedFileName();
        });

        static::updated(function (self $arquivo): void {
            if ($arquivo->wasChanged('upload_arquivo')) {
                $arquivo->normalizeUploadedFileName();
            }
        });

        static::deleted(function (self $arquivo): void {
            if (filled($arquivo->upload_arquivo)) {
                Storage::disk('public')->delete($arquivo->upload_arquivo);
            }
        });
    }

    public function normalizeUploadedFileName(): void
    {
        if (blank($this->upload_arquivo)) {
            return;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($this->upload_arquivo)) {
            return;
        }

        $extension = pathinfo($this->upload_arquivo, PATHINFO_EXTENSION);
        $directory = trim((string) pathinfo($this->upload_arquivo, PATHINFO_DIRNAME), '.\\/');
        $targetDirectory = $directory !== '' ? $directory : 'arquivos-diarios';
        $targetPath = $targetDirectory . '/' . $this->getKey() . '_' . now()->format('dmY') . ($extension ? '.' . Str::lower($extension) : '');

        if ($this->upload_arquivo === $targetPath) {
            return;
        }

        if ($disk->exists($targetPath)) {
            $disk->delete($targetPath);
        }

        $disk->move($this->upload_arquivo, $targetPath);

        $this->forceFill(['upload_arquivo' => $targetPath])->saveQuietly();
    }
}
