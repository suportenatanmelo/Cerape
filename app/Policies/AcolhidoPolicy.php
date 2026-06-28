<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Acolhido;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'Acolhido');
    }

    public function view(User $authUser, Acolhido $acolhido): bool
    {
        return $this->allows($authUser, 'view', 'Acolhido')
            && $authUser->canAccessAcolhido($acolhido->getKey());
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'Acolhido');
    }

    public function update(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'Acolhido');
    }

    public function delete(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'Acolhido');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'Acolhido');
    }

    public function restore(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'Acolhido');
    }

    public function forceDelete(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'Acolhido');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'Acolhido');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'Acolhido');
    }

    public function replicate(User $authUser, Acolhido $acolhido): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'Acolhido');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'Acolhido');
    }
}
