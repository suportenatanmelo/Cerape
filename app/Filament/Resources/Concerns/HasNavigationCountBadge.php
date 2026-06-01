<?php

namespace App\Filament\Resources\Concerns;

trait HasNavigationCountBadge
{
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'gray';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total de registros cadastrados';
    }
}
