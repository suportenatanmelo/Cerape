<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;

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
        static::saved(function (self $arquivo): void {
            ImageStorageNaming::syncStoredFile($arquivo, 'upload_arquivo', 'upload-arquivo', $arquivo->titulo);
        });
        static::deleted(function (self $arquivo): void {
            ImageStorageNaming::removeStoredPath($arquivo->upload_arquivo);
        });
    }
}
