<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PortalContext
{
    public static function isFamilyUser(Authenticatable | null $user = null): bool
    {
        $user ??= auth()->user();

        return $user instanceof User && filled($user->acolhido_id);
    }

    public static function brandName(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Portal da Familia'
            : 'CADASTROS';
    }

    public static function portalNavigationGroup(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Portal da Familia'
            : 'CADASTROS';
    }

    public static function greeting(Authenticatable | null $user = null): string
    {
        return static::isFamilyUser($user)
            ? 'Acompanhamento com carinho, clareza e proximidade.'
            : 'Gestao institucional e acompanhamento clinico.';
    }
}
