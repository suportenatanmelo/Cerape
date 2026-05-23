<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DemandaAcolhido;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandaAcolhidoPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'DemandaAcolhido');
    }

    public function view(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $this->allows($authUser, 'view', 'DemandaAcolhido')
            && $authUser->canAccessAcolhido($demandaAcolhido->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'DemandaAcolhido');
    }

    public function update(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'DemandaAcolhido');
    }

    public function delete(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'DemandaAcolhido');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'DemandaAcolhido');
    }

    public function restore(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'DemandaAcolhido');
    }

    public function forceDelete(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'DemandaAcolhido');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'DemandaAcolhido');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'DemandaAcolhido');
    }

    public function replicate(User $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'DemandaAcolhido');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'DemandaAcolhido');
    }
}
