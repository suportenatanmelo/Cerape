<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AcolhidoAccess
{
    public static function linkedAcolhidoId(Authenticatable | null $user): ?int
    {
        if (! $user instanceof User || blank($user->acolhido_id)) {
            return null;
        }

        return (int) $user->acolhido_id;
    }

    public static function hasLinkedAcolhido(Authenticatable | null $user): bool
    {
        return static::linkedAcolhidoId($user) !== null;
    }

    public static function canAccessAcolhido(Authenticatable | null $user, ?int $acolhidoId): bool
    {
        $linkedAcolhidoId = static::linkedAcolhidoId($user);

        if ($linkedAcolhidoId === null) {
            return true;
        }

        return $acolhidoId !== null && $linkedAcolhidoId === $acolhidoId;
    }

    public static function scopeQueryToAcolhido(Builder $query, Authenticatable | null $user, string $column = 'acolhido_id'): Builder
    {
        $linkedAcolhidoId = static::linkedAcolhidoId($user);

        if ($linkedAcolhidoId === null) {
            return $query;
        }

        return $query->where($column, $linkedAcolhidoId);
    }

    /**
     * @return Collection<int, User>
     */
    public static function notificationRecipientsForAcolhido(int $acolhidoId): Collection
    {
        return User::query()
            ->where(function (Builder $query) use ($acolhidoId): void {
                $query
                    ->whereNull('acolhido_id')
                    ->orWhere('acolhido_id', $acolhidoId);
            })
            ->get();
    }
}
