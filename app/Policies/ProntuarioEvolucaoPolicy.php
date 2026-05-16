<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProntuarioEvolucao;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProntuarioEvolucaoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'ProntuarioEvolucao');
    }

    public function view(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'ProntuarioEvolucao')
            && $authUser->canAccessAcolhido($prontuarioEvolucao->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'ProntuarioEvolucao');
    }

    public function update(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'ProntuarioEvolucao');
    }

    public function delete(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'ProntuarioEvolucao');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'ProntuarioEvolucao');
    }

    public function restore(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'ProntuarioEvolucao');
    }

    public function forceDelete(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'ProntuarioEvolucao');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'ProntuarioEvolucao');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'ProntuarioEvolucao');
    }

    public function replicate(User $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'ProntuarioEvolucao');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'ProntuarioEvolucao');
    }

}
