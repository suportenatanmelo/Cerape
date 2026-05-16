<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class ShieldPermission
{
    public static function allows(User $user, string $ability, string $subject): bool
    {
        foreach (static::candidates($ability, $subject) as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    public static function candidates(string $ability, string $subject): array
    {
        $snakeAbility = Str::snake($ability);
        $snakeSubject = Str::snake($subject);
        $studlyAbility = Str::studly($ability);
        $studlySubject = str_replace('_', '', Str::studly($subject));

        return array_values(array_unique([
            "{$snakeAbility}:{$snakeSubject}",
            "{$studlyAbility}:{$studlySubject}",
        ]));
    }
}
