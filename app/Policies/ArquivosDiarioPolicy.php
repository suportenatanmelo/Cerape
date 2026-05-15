<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ArquivosDiario;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArquivosDiarioPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ArquivosDiario');
    }

    public function view(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('View:ArquivosDiario');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ArquivosDiario');
    }

    public function update(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('Update:ArquivosDiario');
    }

    public function delete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('Delete:ArquivosDiario');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ArquivosDiario');
    }

    public function restore(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('Restore:ArquivosDiario');
    }

    public function forceDelete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('ForceDelete:ArquivosDiario');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ArquivosDiario');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ArquivosDiario');
    }

    public function replicate(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('Replicate:ArquivosDiario');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ArquivosDiario');
    }

}