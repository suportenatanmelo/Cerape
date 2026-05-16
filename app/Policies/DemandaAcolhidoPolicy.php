<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DemandaAcolhido;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandaAcolhidoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'DemandaAcolhido');
    }

    public function view(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'DemandaAcolhido')
            && $authUser->canAccessAcolhido($demandaAcolhido->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'DemandaAcolhido');
    }

    public function update(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'DemandaAcolhido');
    }

    public function delete(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'DemandaAcolhido');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'DemandaAcolhido');
    }

    public function restore(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'DemandaAcolhido');
    }

    public function forceDelete(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'DemandaAcolhido');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'DemandaAcolhido');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'DemandaAcolhido');
    }

    public function replicate(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'DemandaAcolhido');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'DemandaAcolhido');
    }

}
