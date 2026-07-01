<?php

function canAccess(string $permission): bool
{
    $user = auth()->user();

    if (! $user) return false;

    if ($user->hasRole(config('filament-shield.super_admin.name', 'super_admin'))) return true;

    return $user->can($permission);
}
