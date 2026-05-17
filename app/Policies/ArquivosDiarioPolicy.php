<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArquivosDiario;
use App\Models\User;
use App\Support\ShieldPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArquivosDiarioPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'viewAny', 'ArquivosDiario');
    }

    public function view(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'view', 'ArquivosDiario');
    }

    public function create(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'create', 'ArquivosDiario');
    }

    public function update(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'update', 'ArquivosDiario');
    }

    public function delete(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'delete', 'ArquivosDiario');
    }

    public function deleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'deleteAny', 'ArquivosDiario');
    }

    public function restore(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'restore', 'ArquivosDiario');
    }

    public function forceDelete(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'forceDelete', 'ArquivosDiario');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'forceDeleteAny', 'ArquivosDiario');
    }

    public function restoreAny(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'restoreAny', 'ArquivosDiario');
    }

    public function replicate(User $authUser, ArquivosDiario $arquivosDiario): bool
    {
        return ShieldPermission::allows($authUser, 'replicate', 'ArquivosDiario');
    }

    public function reorder(User $authUser): bool
    {
        return ShieldPermission::allows($authUser, 'reorder', 'ArquivosDiario');
    }
}
