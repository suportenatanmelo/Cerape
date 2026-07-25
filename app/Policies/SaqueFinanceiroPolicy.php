<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SaqueFinanceiro;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaqueFinanceiroPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SaqueFinanceiro');
    }

    public function view(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('View:SaqueFinanceiro');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SaqueFinanceiro');
    }

    public function update(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('Update:SaqueFinanceiro');
    }

    public function delete(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('Delete:SaqueFinanceiro');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SaqueFinanceiro');
    }

    public function restore(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('Restore:SaqueFinanceiro');
    }

    public function forceDelete(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('ForceDelete:SaqueFinanceiro');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SaqueFinanceiro');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SaqueFinanceiro');
    }

    public function replicate(AuthUser $authUser, SaqueFinanceiro $saqueFinanceiro): bool
    {
        return $authUser->can('Replicate:SaqueFinanceiro');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SaqueFinanceiro');
    }

}