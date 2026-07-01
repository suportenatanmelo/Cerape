<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reuniao;
use App\Policies\Concerns\AuthorizesShieldPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ReuniaoPolicy
{
    use AuthorizesShieldPermissions;
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'viewAny', 'Reuniao');
    }

    public function view(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'view', 'Reuniao');
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'create', 'Reuniao');
    }

    public function update(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'update', 'Reuniao');
    }

    public function delete(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'delete', 'Reuniao');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'deleteAny', 'Reuniao');
    }

    public function restore(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'restore', 'Reuniao');
    }

    public function forceDelete(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'forceDelete', 'Reuniao');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'forceDeleteAny', 'Reuniao');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'restoreAny', 'Reuniao');
    }

    public function replicate(AuthUser $authUser, Reuniao $reuniao): bool
    {
        return $this->allows($authUser, 'replicate', 'Reuniao');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->allows($authUser, 'reorder', 'Reuniao');
    }
}
