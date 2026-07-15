<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DiariaTrabalho;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiariaTrabalhoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DiariaTrabalho');
    }

    public function view(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('View:DiariaTrabalho');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DiariaTrabalho');
    }

    public function update(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('Update:DiariaTrabalho');
    }

    public function delete(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('Delete:DiariaTrabalho');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DiariaTrabalho');
    }

    public function restore(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('Restore:DiariaTrabalho');
    }

    public function forceDelete(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('ForceDelete:DiariaTrabalho');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DiariaTrabalho');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DiariaTrabalho');
    }

    public function replicate(AuthUser $authUser, DiariaTrabalho $diariaTrabalho): bool
    {
        return $authUser->can('Replicate:DiariaTrabalho');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DiariaTrabalho');
    }

}