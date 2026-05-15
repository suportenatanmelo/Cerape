<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AvaliacaoPessoal;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvaliacaoPessoalPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AvaliacaoPessoal');
    }

    public function view(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('View:AvaliacaoPessoal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AvaliacaoPessoal');
    }

    public function update(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('Update:AvaliacaoPessoal');
    }

    public function delete(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('Delete:AvaliacaoPessoal');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AvaliacaoPessoal');
    }

    public function restore(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('Restore:AvaliacaoPessoal');
    }

    public function forceDelete(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('ForceDelete:AvaliacaoPessoal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AvaliacaoPessoal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AvaliacaoPessoal');
    }

    public function replicate(AuthUser $authUser, AvaliacaoPessoal $avaliacaoPessoal): bool
    {
        return $authUser->can('Replicate:AvaliacaoPessoal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AvaliacaoPessoal');
    }

}