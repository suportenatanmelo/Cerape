<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AcolhidoGaleria;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoGaleriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'viewAny', 'AcolhidoGaleria');
    }

    public function view(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'view', 'AcolhidoGaleria');
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'AcolhidoGaleria');
    }

    public function update(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'AcolhidoGaleria');
    }

    public function delete(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'AcolhidoGaleria');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'AcolhidoGaleria');
    }

    public function restore(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'AcolhidoGaleria');
    }

    public function forceDelete(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'AcolhidoGaleria');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'AcolhidoGaleria');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'AcolhidoGaleria');
    }

    public function replicate(User $authUser, AcolhidoGaleria $acolhidoGaleria): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'AcolhidoGaleria');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'AcolhidoGaleria');
    }
}
