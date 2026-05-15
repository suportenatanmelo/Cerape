<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AtividadeDesenvolvida;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtividadeDesenvolvidaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AtividadeDesenvolvida');
    }

    public function view(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('View:AtividadeDesenvolvida');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AtividadeDesenvolvida');
    }

    public function update(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('Update:AtividadeDesenvolvida');
    }

    public function delete(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('Delete:AtividadeDesenvolvida');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AtividadeDesenvolvida');
    }

    public function restore(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('Restore:AtividadeDesenvolvida');
    }

    public function forceDelete(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('ForceDelete:AtividadeDesenvolvida');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AtividadeDesenvolvida');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AtividadeDesenvolvida');
    }

    public function replicate(AuthUser $authUser, AtividadeDesenvolvida $atividadeDesenvolvida): bool
    {
        return $authUser->can('Replicate:AtividadeDesenvolvida');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AtividadeDesenvolvida');
    }

}