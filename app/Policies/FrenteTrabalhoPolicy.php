<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FrenteTrabalho;
use Illuminate\Auth\Access\HandlesAuthorization;

class FrenteTrabalhoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FrenteTrabalho');
    }

    public function view(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('View:FrenteTrabalho');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FrenteTrabalho');
    }

    public function update(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('Update:FrenteTrabalho');
    }

    public function delete(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('Delete:FrenteTrabalho');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FrenteTrabalho');
    }

    public function restore(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('Restore:FrenteTrabalho');
    }

    public function forceDelete(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('ForceDelete:FrenteTrabalho');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FrenteTrabalho');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FrenteTrabalho');
    }

    public function replicate(AuthUser $authUser, FrenteTrabalho $frenteTrabalho): bool
    {
        return $authUser->can('Replicate:FrenteTrabalho');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FrenteTrabalho');
    }

}