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
        return $authUser->can('view_any:arquivos_diario');
    }

    public function view(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('view:arquivos_diario');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create:arquivos_diario');
    }

    public function update(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('update:arquivos_diario');
    }

    public function delete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('delete:arquivos_diario');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any:arquivos_diario');
    }

    public function restore(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('restore:arquivos_diario');
    }

    public function forceDelete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('force_delete:arquivos_diario');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any:arquivos_diario');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any:arquivos_diario');
    }

    public function replicate(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $authUser->can('replicate:arquivos_diario');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder:arquivos_diario');
    }

}