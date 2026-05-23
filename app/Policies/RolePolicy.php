<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Support\ShieldPermission;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny');
    }

    public function view(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'view');
    }

    public function create(User $authUser): bool
    {
        return $this->allows($authUser, 'create');
    }

    public function update(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'update');
    }

    public function delete(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'delete');
    }

    public function deleteAny(User $authUser): bool
    {
        return $this->allows($authUser, 'deleteAny');
    }

    public function restore(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'restore');
    }

    public function forceDelete(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'forceDelete');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $this->allows($authUser, 'forceDeleteAny');
    }

    public function restoreAny(User $authUser): bool
    {
        return $this->allows($authUser, 'restoreAny');
    }

    public function replicate(User $authUser, Role $role): bool
    {
        return $this->allows($authUser, 'replicate');
    }

    public function reorder(User $authUser): bool
    {
        return $this->allows($authUser, 'reorder');
    }

    private function allows(User $authUser, string $ability): bool
    {
        if ($authUser->isRestrictedToAcolhido()) {
            return false;
        }

        if ($authUser->hasRole((string) config('filament-shield.super_admin.name', 'super_admin'))) {
            return true;
        }

        if (ShieldPermission::allows($authUser, $ability, 'Role')) {
            return true;
        }

        foreach (ShieldPermission::candidates($ability, 'Role') as $permission) {
            if ($authUser->can($permission)) {
                return true;
            }
        }

        $legacy = match ($ability) {
            'viewAny' => 'view_any:role',
            'deleteAny' => 'delete_any:role',
            'forceDelete' => 'force_delete:role',
            'forceDeleteAny' => 'force_delete_any:role',
            'restoreAny' => 'restore_any:role',
            default => strtolower($ability) . ':role',
        };

        if ($authUser->can($legacy)) {
            return true;
        }

        $roleModel = Utils::getRoleModel();

        return $authUser->can($ability, $roleModel);
    }
}
