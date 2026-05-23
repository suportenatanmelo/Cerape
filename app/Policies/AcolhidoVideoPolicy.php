<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AcolhidoVideo;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoVideoPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'AcolhidoVideo');
    }

    public function view(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return $this->allows($authUser, 'view', 'AcolhidoVideo')
            && $authUser->canAccessAcolhido($acolhidoVideo->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'AcolhidoVideo');
    }

    public function update(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'AcolhidoVideo');
    }

    public function delete(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'AcolhidoVideo');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'AcolhidoVideo');
    }

    public function restore(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'AcolhidoVideo');
    }

    public function forceDelete(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'AcolhidoVideo');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'AcolhidoVideo');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'AcolhidoVideo');
    }

    public function replicate(User $authUser, AcolhidoVideo $acolhidoVideo): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'AcolhidoVideo');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'AcolhidoVideo');
    }
}
