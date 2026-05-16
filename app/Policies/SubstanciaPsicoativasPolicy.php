<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SubstanciaPsicoativas;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubstanciaPsicoativasPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'SubstanciaPsicoativas');
    }

    public function view(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'SubstanciaPsicoativas')
            && $authUser->canAccessAcolhido($substanciaPsicoativas->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'SubstanciaPsicoativas');
    }

    public function update(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'SubstanciaPsicoativas');
    }

    public function delete(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'SubstanciaPsicoativas');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'SubstanciaPsicoativas');
    }

    public function restore(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'SubstanciaPsicoativas');
    }

    public function forceDelete(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'SubstanciaPsicoativas');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'SubstanciaPsicoativas');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'SubstanciaPsicoativas');
    }

    public function replicate(User $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'SubstanciaPsicoativas');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'SubstanciaPsicoativas');
    }

}
