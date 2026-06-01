<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class PortalResourceAuthorization
{
    public static function canViewAny(?User $user, string $subject): bool
    {
        return $user instanceof User
            && ShieldPermission::allows($user, 'viewAny', $subject);
    }

    public static function canViewRecord(?User $user, string $subject, ?int $acolhidoId): bool
    {
        return $user instanceof User
            && ShieldPermission::allows($user, 'view', $subject)
            && $user->canAccessAcolhido($acolhidoId);
    }

    public static function canManage(?User $user, string $subject, string $ability): bool
    {
        return $user instanceof User
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, $ability, $subject);
    }

    public static function scopeVisibleRecords(Builder $query, ?User $user, string $activeColumn = 'ativo'): Builder
    {
        if ($user instanceof User && PortalContext::isFamilyUser($user)) {
            $query->where($activeColumn, true);
        }

        return $query;
    }
}
