<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Foundation\Auth\User as AuthUser;

trait AuthorizesShieldPermissions
{
    protected function allows(AuthUser $authUser, string $ability, string $subject): bool
    {
        return $authUser instanceof User
            && ShieldPermission::allows($authUser, $ability, $subject);
    }
}
