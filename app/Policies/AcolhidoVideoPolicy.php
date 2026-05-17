<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AcolhidoVideo;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoVideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'viewAny', 'AcolhidoVideo');
    }

    public function view(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'view', 'AcolhidoVideo');
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'AcolhidoVideo');
    }

    public function update(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'AcolhidoVideo');
    }

    public function delete(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'AcolhidoVideo');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'AcolhidoVideo');
    }

    public function restore(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'AcolhidoVideo');
    }

    public function forceDelete(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'AcolhidoVideo');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'AcolhidoVideo');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'AcolhidoVideo');
    }

    public function replicate(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'AcolhidoVideo');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'AcolhidoVideo');
    }
}
