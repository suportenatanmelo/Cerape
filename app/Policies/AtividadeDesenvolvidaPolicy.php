<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AtividadeDesenvolvida;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtividadeDesenvolvidaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'AtividadeDesenvolvida');
    }

    public function view(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'AtividadeDesenvolvida')
            && $authUser->canAccessAcolhido($atividadeDesenvolvida->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'AtividadeDesenvolvida');
    }

    public function update(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'AtividadeDesenvolvida');
    }

    public function delete(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'AtividadeDesenvolvida');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'AtividadeDesenvolvida');
    }

    public function restore(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'AtividadeDesenvolvida');
    }

    public function forceDelete(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'AtividadeDesenvolvida');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'AtividadeDesenvolvida');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'AtividadeDesenvolvida');
    }

    public function replicate(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'AtividadeDesenvolvida');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'AtividadeDesenvolvida');
    }

}
