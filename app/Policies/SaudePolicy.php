<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Saude;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaudePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Saude');
    }

    public function view(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('View:Saude');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Saude');
    }

    public function update(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('Update:Saude');
    }

    public function delete(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('Delete:Saude');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Saude');
    }

    public function restore(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('Restore:Saude');
    }

    public function forceDelete(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('ForceDelete:Saude');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Saude');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Saude');
    }

    public function replicate(AuthUser $authUser, Saude $saude): bool
    {
        return $authUser->can('Replicate:Saude');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Saude');
    }

}