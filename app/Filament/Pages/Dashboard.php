<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardOverviewWidget;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        return Auth::user()?->hasAclPermission(User::PERMISSION_DASHBOARD_VIEW) ?? false;
    }

    public static function canView(): bool
    {
        return self::canAccess();
    }

    public function getTitle(): string
    {
        return 'Painel CERAPE';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardOverviewWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
            'xl' => 3,
        ];
    }
}
