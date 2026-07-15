<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Cms\Models\Seo;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Seo');
    }

    public function view(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('View:Seo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Seo');
    }

    public function update(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('Update:Seo');
    }

    public function delete(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('Delete:Seo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Seo');
    }

    public function restore(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('Restore:Seo');
    }

    public function forceDelete(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('ForceDelete:Seo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Seo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Seo');
    }

    public function replicate(AuthUser $authUser, Seo $seo): bool
    {
        return $authUser->can('Replicate:Seo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Seo');
    }
}
