<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Acolhido;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcolhidoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Acolhido');
    }

    public function view(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('View:Acolhido');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Acolhido');
    }

    public function update(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('Update:Acolhido');
    }

    public function delete(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('Delete:Acolhido');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Acolhido');
    }

    public function restore(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('Restore:Acolhido');
    }

    public function forceDelete(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('ForceDelete:Acolhido');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Acolhido');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Acolhido');
    }

    public function replicate(AuthUser $authUser, Acolhido $acolhido): bool
    {
        return $authUser->can('Replicate:Acolhido');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Acolhido');
    }

}