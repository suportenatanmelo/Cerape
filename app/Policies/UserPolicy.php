<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'User');
    }

    public function view(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'view', 'User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'create', 'User');
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'update', 'User');
    }

    public function delete(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'delete', 'User');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'deleteAny', 'User');
    }

    public function restore(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'restore', 'User');
    }

    public function forceDelete(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'forceDelete', 'User');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'forceDeleteAny', 'User');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'restoreAny', 'User');
    }

    public function replicate(AuthUser $authUser, User $user): bool
    {
        return $this->allows($authUser, 'replicate', 'User');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'reorder', 'User');
    }
}
