<?php

namespace App\Filament\Pages;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
}
