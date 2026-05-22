<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class UserRoleManager
{
    /**
     * @return array{attributes: array<string, mixed>, roles: array<int, mixed>}
     */
    public static function splitFormData(array $data): array
    {
        $roles = Arr::wrap(Arr::pull($data, 'roles', []));

        return [
            'attributes' => $data,
            'roles' => array_values(array_filter($roles, fn (mixed $role): bool => filled($role))),
        ];
    }

    /**
     * @param  array<int, mixed>  $selectedRoles
     */
    public static function syncRoles(User $user, array $selectedRoles): void
    {
        $roleModel = app(config('permission.models.role'));

        $roles = collect($selectedRoles)
            ->map(fn (mixed $role): mixed => is_numeric($role) ? (int) $role : $role)
            ->pipe(function (Collection $roles) use ($roleModel): Collection {
                $ids = $roles->filter(fn (mixed $role): bool => is_int($role))->values();
                $names = $roles->filter(fn (mixed $role): bool => is_string($role))->values();

                return $roleModel::query()
                    ->when($ids->isNotEmpty(), fn ($query) => $query->orWhereIn('id', $ids->all()))
                    ->when($names->isNotEmpty(), fn ($query) => $query->orWhereIn('name', $names->all()))
                    ->get();
            });

        $user->syncRoles($roles);
    }

    public static function hasAssignedRole(User $user): bool
    {
        return $user->roles()->exists();
    }
}
