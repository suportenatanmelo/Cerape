<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DemandaAcolhido;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandaAcolhidoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DemandaAcolhido');
    }

    public function view(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('View:DemandaAcolhido');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DemandaAcolhido');
    }

    public function update(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('Update:DemandaAcolhido');
    }

    public function delete(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('Delete:DemandaAcolhido');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DemandaAcolhido');
    }

    public function restore(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('Restore:DemandaAcolhido');
    }

    public function forceDelete(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('ForceDelete:DemandaAcolhido');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DemandaAcolhido');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DemandaAcolhido');
    }

    public function replicate(AuthUser $authUser, DemandaAcolhido $demandaAcolhido): bool
    {
        return $authUser->can('Replicate:DemandaAcolhido');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DemandaAcolhido');
    }

}