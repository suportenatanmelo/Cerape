<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProntuarioEvolucao;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProntuarioEvolucaoPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'ProntuarioEvolucao');
    }

    public function view(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $this->allows($authUser, 'view', 'ProntuarioEvolucao')
            && $authUser->canAccessAcolhido($prontuarioEvolucao->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'ProntuarioEvolucao');
    }

    public function update(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'ProntuarioEvolucao');
    }

    public function delete(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'ProntuarioEvolucao');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'ProntuarioEvolucao');
    }

    public function restore(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'ProntuarioEvolucao');
    }

    public function forceDelete(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'ProntuarioEvolucao');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'ProntuarioEvolucao');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'ProntuarioEvolucao');
    }

    public function replicate(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'ProntuarioEvolucao');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'ProntuarioEvolucao');
    }
}
