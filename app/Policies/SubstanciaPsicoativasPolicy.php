<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SubstanciaPsicoativas;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubstanciaPsicoativasPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SubstanciaPsicoativas');
    }

    public function view(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('View:SubstanciaPsicoativas');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SubstanciaPsicoativas');
    }

    public function update(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('Update:SubstanciaPsicoativas');
    }

    public function delete(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('Delete:SubstanciaPsicoativas');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SubstanciaPsicoativas');
    }

    public function restore(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('Restore:SubstanciaPsicoativas');
    }

    public function forceDelete(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('ForceDelete:SubstanciaPsicoativas');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SubstanciaPsicoativas');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SubstanciaPsicoativas');
    }

    public function replicate(AuthUser $authUser, SubstanciaPsicoativas $substanciaPsicoativas): bool
    {
        return $authUser->can('Replicate:SubstanciaPsicoativas');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SubstanciaPsicoativas');
    }

}