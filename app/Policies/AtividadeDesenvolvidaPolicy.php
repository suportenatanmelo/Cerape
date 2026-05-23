<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AtividadeDesenvolvida;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtividadeDesenvolvidaPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'AtividadeDesenvolvida');
    }

    public function view(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $this->allows($authUser, 'view', 'AtividadeDesenvolvida')
            && $authUser->canAccessAcolhido($atividadeDesenvolvida->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'AtividadeDesenvolvida');
    }

    public function update(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'AtividadeDesenvolvida');
    }

    public function delete(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'AtividadeDesenvolvida');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'AtividadeDesenvolvida');
    }

    public function restore(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'AtividadeDesenvolvida');
    }

    public function forceDelete(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'AtividadeDesenvolvida');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'AtividadeDesenvolvida');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'AtividadeDesenvolvida');
    }

    public function replicate(User $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'AtividadeDesenvolvida');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'AtividadeDesenvolvida');
    }
}
