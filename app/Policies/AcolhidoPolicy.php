<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'Acolhido');
    }

    public function view(User $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('view:acolhido')
            || ShieldPermission::allows($authUser, 'view', 'Acolhido')
            ? $authUser->canAccessAcolhido($acolhido->getKey())
            : false;
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'Acolhido');
    }

    public function update(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'Acolhido');
    }

    public function delete(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'Acolhido');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'Acolhido');
    }

    public function restore(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'Acolhido');
    }

    public function forceDelete(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'Acolhido');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'Acolhido');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'Acolhido');
    }

    public function replicate(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'Acolhido');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'Acolhido');
    }

}
