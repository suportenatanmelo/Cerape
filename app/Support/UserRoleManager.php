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
            'roles' => array_values(array_unique(array_filter($roles, fn (mixed $role): bool => filled($role)))),
        ];
    }

    /**
     * @param  array<int, mixed>  $selectedRoles
     */
    public static function syncRoles(User $user, array $selectedRoles): void
    {
        $beforeRoles = $user->roles->pluck('name')->values()->all();
        $roleModel = app(config('permission.models.role'));

        $roles = collect($selectedRoles)
            ->filter(fn (mixed $role): bool => filled($role))
            ->map(fn (mixed $role): mixed => is_numeric($role) ? (int) $role : $role)
            ->unique()
            ->values();

        if ($roles->isEmpty()) {
            $user->syncRoles([]);
            $afterRoles = [];
        } else {
            $roles = $roles
                ->pipe(function (Collection $roles) use ($roleModel): Collection {
                    $ids = $roles->filter(fn (mixed $role): bool => is_int($role))->values();
                    $names = $roles->filter(fn (mixed $role): bool => is_string($role))->values();

                    return $roleModel::query()
                        ->where(function ($query) use ($ids, $names): void {
                            if ($ids->isNotEmpty()) {
                                $query->whereIn('id', $ids->all());
                            }

                            if ($names->isNotEmpty()) {
                                $method = $ids->isNotEmpty() ? 'orWhereIn' : 'whereIn';
                                $query->{$method}('name', $names->all());
                            }
                        })
                        ->get();
                });

            $user->syncRoles($roles);
            $afterRoles = $user->roles->pluck('name')->values()->all();
        }

        if ($beforeRoles !== $afterRoles) {
            app(ActivityLogger::class)->custom(
                'Usuários',
                'update',
                'Atualizou perfis de acesso do usuário ' . $user->name,
                $user,
                ['roles' => $beforeRoles],
                ['roles' => $afterRoles],
            );
        }
    }

    public static function hasAssignedRole(User $user): bool
    {
        return $user->roles()->exists();
    }
}