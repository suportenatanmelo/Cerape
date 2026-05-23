<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArquivosDiario;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ArquivosDiarioPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'ArquivosDiario');
    }

    public function view(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'view', 'ArquivosDiario');
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'create', 'ArquivosDiario');
    }

    public function update(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'update', 'ArquivosDiario');
    }

    public function delete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'delete', 'ArquivosDiario');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'deleteAny', 'ArquivosDiario');
    }

    public function restore(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'restore', 'ArquivosDiario');
    }

    public function forceDelete(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'forceDelete', 'ArquivosDiario');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'forceDeleteAny', 'ArquivosDiario');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'restoreAny', 'ArquivosDiario');
    }

    public function replicate(AuthUser $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return $this->allows($authUser, 'replicate', 'ArquivosDiario');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'reorder', 'ArquivosDiario');
    }
}
