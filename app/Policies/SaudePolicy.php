<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Saude;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaudePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'Saude');
    }

    public function view(User $authUser, Saude $saude): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'Saude')
            && $authUser->canAccessAcolhido($saude->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'Saude');
    }

    public function update(User $authUser, Saude $saude): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'Saude');
    }

    public function delete(User $authUser, Saude $saude): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'Saude');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'Saude');
    }

    public function restore(User $authUser, Saude $saude): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'Saude');
    }

    public function forceDelete(User $authUser, Saude $saude): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'Saude');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'Saude');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'Saude');
    }

    public function replicate(User $authUser, Saude $saude): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'Saude');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'Saude');
    }

}
