<?php

namespace App\Policies;

use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'User');
    }

    public function view(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'User');
    }

    public function create(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'create', 'User');
    }

    public function update(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'update', 'User');
    }

    public function delete(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'delete', 'User');
    }

    public function deleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'deleteAny', 'User');
    }

    public function restore(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'restore', 'User');
    }

    public function forceDelete(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'forceDelete', 'User');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'forceDeleteAny', 'User');
    }

    public function restoreAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'restoreAny', 'User');
    }

    public function replicate(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'replicate', 'User');
    }

    public function reorder(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'reorder', 'User');
    }
}
