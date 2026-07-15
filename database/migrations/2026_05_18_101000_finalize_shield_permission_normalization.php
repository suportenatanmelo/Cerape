<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $rolePermissionsTable = config('permission.table_names.role_has_permissions');
        $modelPermissionsTable = config('permission.table_names.model_has_permissions');
        $permissionsTable = config('permission.table_names.permissions');

        DB::transaction(function () use ($permissionsTable, $rolePermissionsTable, $modelPermissionsTable): void {
            $permissions = collect(DB::table($permissionsTable)->orderBy('id')->get());

            $permissions
                ->groupBy(fn (object $permission): string => $permission->guard_name . '|' . ($this->canonicalPermissionName($permission->name) ?? $permission->name))
                ->each(function (Collection $group) use ($permissionsTable, $rolePermissionsTable, $modelPermissionsTable): void {
                    /** @var object $first */
                    $first = $group->first();
                    $canonicalName = $this->canonicalPermissionName($first->name);

                    if ($canonicalName === null) {
                        return;
                    }

                    $primary = $group->firstWhere('name', $canonicalName) ?? $first;

                    if ($primary->name !== $canonicalName) {
                        DB::table($permissionsTable)
                            ->where('id', $primary->id)
                            ->update([
                                'name' => $canonicalName,
                                'updated_at' => now(),
                            ]);
                    }

                    $group
                        ->filter(fn (object $permission): bool => $permission->id !== $primary->id)
                        ->each(function (object $duplicate) use ($primary, $rolePermissionsTable, $modelPermissionsTable, $permissionsTable): void {
                            DB::table($rolePermissionsTable)
                                ->where('permission_id', $duplicate->id)
                                ->get()
                                ->each(function (object $pivot) use ($primary, $rolePermissionsTable): void {
                                    DB::table($rolePermissionsTable)->updateOrInsert([
                                        'permission_id' => $primary->id,
                                        'role_id' => $pivot->role_id,
                                    ], []);
                                });

                            DB::table($modelPermissionsTable)
                                ->where('permission_id', $duplicate->id)
                                ->get()
                                ->each(function (object $pivot) use ($primary, $modelPermissionsTable): void {
                                    DB::table($modelPermissionsTable)->updateOrInsert([
                                        'permission_id' => $primary->id,
                                        'model_type' => $pivot->model_type,
                                        'model_id' => $pivot->model_id,
                                    ], []);
                                });

                            DB::table($rolePermissionsTable)->where('permission_id', $duplicate->id)->delete();
                            DB::table($modelPermissionsTable)->where('permission_id', $duplicate->id)->delete();
                            DB::table($permissionsTable)->where('id', $duplicate->id)->delete();
                        });
                });
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        //
    }

    private function canonicalPermissionName(string $permissionName): ?string
    {
        if (! str_contains($permissionName, ':')) {
            return null;
        }

        [$ability, $subject] = explode(':', $permissionName, 2);

        return Str::studly($ability) . ':' . str_replace('_', '', Str::studly($subject));
    }
};
