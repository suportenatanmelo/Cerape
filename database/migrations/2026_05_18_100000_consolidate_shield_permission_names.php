<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $rolePermissionsTable = config('permission.table_names.role_has_permissions');
        $modelPermissionsTable = config('permission.table_names.model_has_permissions');

        DB::transaction(function () use ($rolePermissionsTable, $modelPermissionsTable): void {
            Permission::query()
                ->orderBy('id')
                ->get()
                ->each(function (Permission $permission) use ($rolePermissionsTable, $modelPermissionsTable): void {
                    $canonicalName = $this->canonicalPermissionName($permission->name);

                    if (($canonicalName === null) || ($canonicalName === $permission->name)) {
                        return;
                    }

                    $canonicalPermission = Permission::query()->firstOrCreate([
                        'name' => $canonicalName,
                        'guard_name' => $permission->guard_name,
                    ]);

                    DB::table($rolePermissionsTable)
                        ->where('permission_id', $permission->getKey())
                        ->get()
                        ->each(function (object $pivot) use ($rolePermissionsTable, $canonicalPermission): void {
                            DB::table($rolePermissionsTable)->updateOrInsert([
                                'permission_id' => $canonicalPermission->getKey(),
                                'role_id' => $pivot->role_id,
                            ], []);
                        });

                    DB::table($modelPermissionsTable)
                        ->where('permission_id', $permission->getKey())
                        ->get()
                        ->each(function (object $pivot) use ($modelPermissionsTable, $canonicalPermission): void {
                            DB::table($modelPermissionsTable)->updateOrInsert([
                                'permission_id' => $canonicalPermission->getKey(),
                                'model_type' => $pivot->model_type,
                                'model_id' => $pivot->model_id,
                            ], []);
                        });

                    DB::table($rolePermissionsTable)
                        ->where('permission_id', $permission->getKey())
                        ->delete();

                    DB::table($modelPermissionsTable)
                        ->where('permission_id', $permission->getKey())
                        ->delete();

                    $permission->delete();
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
