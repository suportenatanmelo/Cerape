<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CuratorMedia;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class CuratorMediaPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'CuratorMedia');
    }

    public function view(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return $this->allows($authUser, 'view', 'CuratorMedia')
            && $authUser->canAccessAcolhido($curatorMedia->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'CuratorMedia');
    }

    public function update(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'CuratorMedia');
    }

    public function delete(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'CuratorMedia');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'CuratorMedia');
    }

    public function restore(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'CuratorMedia');
    }

    public function forceDelete(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'CuratorMedia');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'CuratorMedia');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'CuratorMedia');
    }

    public function replicate(User $authUser, CuratorMedia $curatorMedia): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'CuratorMedia');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'CuratorMedia');
    }
}
