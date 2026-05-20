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
        return $this->allows($authUser, 'viewAny');
    }

    public function view(User $authUser, CuratorMedia $media): bool
    {
        return $this->allows($authUser, 'view')
            && $authUser->canAccessAcolhido($media->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create');
    }

    public function update(User $authUser, CuratorMedia $media): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update');
    }

    public function delete(User $authUser, CuratorMedia $media): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny');
    }

    public function restore(User $authUser, CuratorMedia $media): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny');
    }

    public function forceDelete(User $authUser, CuratorMedia $media): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny');
    }

    public function replicate(User $authUser, CuratorMedia $media): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder');
    }

    private function allows(User $authUser, string $ability): bool
    {
        return ShieldPermission::allows($authUser, $ability, 'Media')
            || ShieldPermission::allows($authUser, $ability, 'CuratorMedia');
    }
}
