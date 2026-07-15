<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(config('filament-shield.super_admin.name', 'super_admin')) || $user->can('view_any_audit');
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->hasRole(config('filament-shield.super_admin.name', 'super_admin')) || $user->can('view_audit');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $user->hasRole(config('filament-shield.super_admin.name', 'super_admin')) || $user->can('delete_audit');
    }

    public function restore(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function forceDelete(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }
}
