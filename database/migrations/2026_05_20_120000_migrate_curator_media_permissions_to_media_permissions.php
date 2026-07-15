<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $abilities = [
            'ViewAny',
            'View',
            'Create',
            'Update',
            'Delete',
            'DeleteAny',
            'Restore',
            'RestoreAny',
            'ForceDelete',
            'ForceDeleteAny',
            'Replicate',
            'Reorder',
        ];

        foreach ($abilities as $ability) {
            $legacyPermission = Permission::query()->where('name', "{$ability}:CuratorMedia")->first();
            $canonicalPermission = Permission::query()->firstOrCreate([
                'name' => "{$ability}:Media",
                'guard_name' => config('auth.defaults.guard', 'web'),
            ]);

            if (! $legacyPermission instanceof Permission) {
                continue;
            }

            DB::table(config('permission.table_names.role_has_permissions'))
                ->where('permission_id', $legacyPermission->getKey())
                ->get()
                ->each(function (object $pivot) use ($canonicalPermission): void {
                    DB::table(config('permission.table_names.role_has_permissions'))->updateOrInsert(
                        [
                            'permission_id' => $canonicalPermission->getKey(),
                            'role_id' => $pivot->role_id,
                        ],
                        []
                    );
                });

            DB::table(config('permission.table_names.model_has_permissions'))
                ->where('permission_id', $legacyPermission->getKey())
                ->get()
                ->each(function (object $pivot) use ($canonicalPermission): void {
                    DB::table(config('permission.table_names.model_has_permissions'))->updateOrInsert(
                        [
                            'permission_id' => $canonicalPermission->getKey(),
                            'model_type' => $pivot->model_type,
                            'model_id' => $pivot->model_id,
                        ],
                        []
                    );
                });

            DB::table(config('permission.table_names.role_has_permissions'))
                ->where('permission_id', $legacyPermission->getKey())
                ->delete();

            DB::table(config('permission.table_names.model_has_permissions'))
                ->where('permission_id', $legacyPermission->getKey())
                ->delete();

            $legacyPermission->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        //
    }
};
