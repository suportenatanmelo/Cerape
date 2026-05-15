<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProntuarioEvolucao;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProntuarioEvolucaoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProntuarioEvolucao');
    }

    public function view(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('View:ProntuarioEvolucao');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProntuarioEvolucao');
    }

    public function update(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('Update:ProntuarioEvolucao');
    }

    public function delete(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('Delete:ProntuarioEvolucao');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProntuarioEvolucao');
    }

    public function restore(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('Restore:ProntuarioEvolucao');
    }

    public function forceDelete(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('ForceDelete:ProntuarioEvolucao');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProntuarioEvolucao');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProntuarioEvolucao');
    }

    public function replicate(AuthUser $authUser, ProntuarioEvolucao $prontuarioEvolucao): bool
    {
        return $authUser->can('Replicate:ProntuarioEvolucao');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProntuarioEvolucao');
    }

}