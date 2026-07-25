<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmpresaParceira;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpresaParceiraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmpresaParceira');
    }

    public function view(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('View:EmpresaParceira');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmpresaParceira');
    }

    public function update(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('Update:EmpresaParceira');
    }

    public function delete(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('Delete:EmpresaParceira');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EmpresaParceira');
    }

    public function restore(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('Restore:EmpresaParceira');
    }

    public function forceDelete(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('ForceDelete:EmpresaParceira');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmpresaParceira');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmpresaParceira');
    }

    public function replicate(AuthUser $authUser, EmpresaParceira $empresaParceira): bool
    {
        return $authUser->can('Replicate:EmpresaParceira');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmpresaParceira');
    }

}