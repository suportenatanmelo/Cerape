<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AcolhidoGaleria;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoGaleriaPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'AcolhidoGaleria');
    }

    public function view(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return $this->allows($authUser, 'view', 'AcolhidoGaleria')
            && $authUser->canAccessAcolhido($acolhidoGaleria->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'AcolhidoGaleria');
    }

    public function update(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'AcolhidoGaleria');
    }

    public function delete(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'AcolhidoGaleria');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'AcolhidoGaleria');
    }

    public function restore(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'AcolhidoGaleria');
    }

    public function forceDelete(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'AcolhidoGaleria');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'AcolhidoGaleria');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'AcolhidoGaleria');
    }

    public function replicate(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'AcolhidoGaleria');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'AcolhidoGaleria');
    }
}
