<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CmsContent extends Model
{
    use HasFactory;

    public const TYPE_HOME_BLOCK = 'home_block';
    public const TYPE_TREATMENT = 'treatment';
    public const TYPE_NEWS = 'news';
    public const TYPE_EVENT = 'event';
    public const TYPE_TESTIMONIAL = 'testimonial';
    public const TYPE_PARTNER = 'partner';
    public const TYPE_FAQ = 'faq';
    public const TYPE_BANNER = 'banner';
    public const TYPE_POPUP = 'popup';
    public const TYPE_MENU_ITEM = 'menu_item';
    public const TYPE_FOOTER_WIDGET = 'footer_widget';
    public const TYPE_SOCIAL_LINK = 'social_link';

    public const TYPES = [
        self::TYPE_HOME_BLOCK => 'Bloco da Home',
        self::TYPE_TREATMENT => 'Tratamento',
        self::TYPE_NEWS => 'Notícia',
        self::TYPE_EVENT => 'Evento',
        self::TYPE_TESTIMONIAL => 'Depoimento',
        self::TYPE_PARTNER => 'Parceiro',
        self::TYPE_FAQ => 'FAQ',
        self::TYPE_BANNER => 'Banner',
        self::TYPE_POPUP => 'Popup',
        self::TYPE_MENU_ITEM => 'Menu',
        self::TYPE_FOOTER_WIDGET => 'Rodapé',
        self::TYPE_SOCIAL_LINK => 'Rede social',
    ];

    protected $fillable = [
        'type',
        'title',
        'slug',
        'subtitle',
        'summary',
        'content',
        'image_path',
        'mobile_image_path',
        'icon',
        'cta_label',
        'cta_url',
        'external_url',
        'category',
        'tags',
        'links',
        'settings',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image_path',
        'starts_at',
        'ends_at',
        'position',
        'is_featured',
        'is_active',
        'hidden',
    ];

    protected $casts = [
        'tags' => 'array',
        'links' => 'array',
        'settings' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'hidden' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $content): void {
            ImageStorageNaming::syncStoredImage($content, 'image_path', 'cms', $content->title);
            ImageStorageNaming::syncStoredImage($content, 'mobile_image_path', 'cms', $content->title . ' mobile');
            ImageStorageNaming::syncStoredImage($content, 'og_image_path', 'cms', $content->title . ' og');
        });
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->visible()
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function imageUrl(?string $field = 'image_path'): ?string
    {
        $path = $this->{$field};

        if (! filled($path)) {
            return null;
        }

        $path = ltrim((string) $path, '/');
        $path = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;

        return Storage::disk('public')->url($path);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
