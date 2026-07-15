<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\GeradorAtividade;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class GeradorAtividadePolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'GeradorAtividade');
    }

    public function view(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'view', 'GeradorAtividade');
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'create', 'GeradorAtividade');
    }

    public function update(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'update', 'GeradorAtividade');
    }

    public function delete(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'delete', 'GeradorAtividade');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'deleteAny', 'GeradorAtividade');
    }

    public function restore(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'restore', 'GeradorAtividade');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'restoreAny', 'GeradorAtividade');
    }

    public function forceDelete(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'forceDelete', 'GeradorAtividade');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'forceDeleteAny', 'GeradorAtividade');
    }

    public function replicate(AuthUser $authUser, GeradorAtividade $geradorAtividade): bool
    {
        return $this->allows($authUser, 'replicate', 'GeradorAtividade');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'reorder', 'GeradorAtividade');
    }
}
