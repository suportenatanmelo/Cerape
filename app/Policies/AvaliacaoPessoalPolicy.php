<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AvaliacaoPessoal;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvaliacaoPessoalPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'AvaliacaoPessoal');
    }

    public function view(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'AvaliacaoPessoal')
            && $authUser->canAccessAcolhido($avaliacaoPessoal->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'create', 'AvaliacaoPessoal');
    }

    public function update(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'update', 'AvaliacaoPessoal');
    }

    public function delete(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'delete', 'AvaliacaoPessoal');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'deleteAny', 'AvaliacaoPessoal');
    }

    public function restore(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restore', 'AvaliacaoPessoal');
    }

    public function forceDelete(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDelete', 'AvaliacaoPessoal');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'forceDeleteAny', 'AvaliacaoPessoal');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'restoreAny', 'AvaliacaoPessoal');
    }

    public function replicate(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'replicate', 'AvaliacaoPessoal');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && ShieldPermission::allows($authUser, 'reorder', 'AvaliacaoPessoal');
    }

}
