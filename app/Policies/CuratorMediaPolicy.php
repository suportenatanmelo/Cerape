<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CuratorMedia;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class CuratorMediaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'Media');
    }

    public function view(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'Media');
    }

    public function create(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'create', 'Media');
    }

    public function update(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'update', 'Media');
    }

    public function delete(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'delete', 'Media');
    }

    public function deleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'deleteAny', 'Media');
    }

    public function restore(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'restore', 'Media');
    }

    public function restoreAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'restoreAny', 'Media');
    }

    public function forceDelete(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'forceDelete', 'Media');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'forceDeleteAny', 'Media');
    }

    public function replicate(User $authUser, CuratorMedia $media): bool
    {
        return ShieldPermission::allows($authUser, 'replicate', 'Media');
    }

    public function reorder(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'reorder', 'Media');
    }
}
