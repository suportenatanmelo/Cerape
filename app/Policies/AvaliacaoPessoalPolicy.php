<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AvaliacaoPessoal;
use App\Models\User;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvaliacaoPessoalPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'AvaliacaoPessoal');
    }

    public function view(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $this->allows($authUser, 'view', 'AvaliacaoPessoal')
            && $authUser->canAccessAcolhido($avaliacaoPessoal->acolhido_id);
    }

    public function create(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'create', 'AvaliacaoPessoal');
    }

    public function update(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'update', 'AvaliacaoPessoal');
    }

    public function delete(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'delete', 'AvaliacaoPessoal');
    }

    public function deleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'deleteAny', 'AvaliacaoPessoal');
    }

    public function restore(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restore', 'AvaliacaoPessoal');
    }

    public function forceDelete(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDelete', 'AvaliacaoPessoal');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'forceDeleteAny', 'AvaliacaoPessoal');
    }

    public function restoreAny(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'restoreAny', 'AvaliacaoPessoal');
    }

    public function replicate(User $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'replicate', 'AvaliacaoPessoal');
    }

    public function reorder(User $authUser): bool
    {
        return ! $authUser->isRestrictedToAcolhido()
            && $this->allows($authUser, 'reorder', 'AvaliacaoPessoal');
    }
}
