<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Cms\Models\Block;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlockPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Block');
    }

    public function view(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('View:Block');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Block');
    }

    public function update(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('Update:Block');
    }

    public function delete(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('Delete:Block');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Block');
    }

    public function restore(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('Restore:Block');
    }

    public function forceDelete(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('ForceDelete:Block');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Block');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Block');
    }

    public function replicate(AuthUser $authUser, Block $block): bool
    {
        return $authUser->can('Replicate:Block');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Block');
    }
}
