<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThemePalette;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemePalettePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThemePalette');
    }

    public function view(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('View:ThemePalette');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThemePalette');
    }

    public function update(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('Update:ThemePalette');
    }

    public function delete(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('Delete:ThemePalette');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ThemePalette');
    }

    public function restore(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('Restore:ThemePalette');
    }

    public function forceDelete(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('ForceDelete:ThemePalette');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThemePalette');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThemePalette');
    }

    public function replicate(AuthUser $authUser, ThemePalette $themePalette): bool
    {
        return $authUser->can('Replicate:ThemePalette');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThemePalette');
    }

}