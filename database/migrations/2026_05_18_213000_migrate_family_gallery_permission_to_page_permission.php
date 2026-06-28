<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $legacyPermission = Permission::query()->where('name', 'View:AcolhidoGalleryPortal')->first();
        $newPermission = Permission::query()->firstOrCreate([
            'name' => 'View:FamilyGallery',
            'guard_name' => 'web',
        ]);

        if (! $legacyPermission instanceof Permission) {
            return;
        }

        DB::table(config('permission.table_names.role_has_permissions'))
            ->where('permission_id', $legacyPermission->getKey())
            ->get()
            ->each(function (object $pivot) use ($newPermission): void {
                DB::table(config('permission.table_names.role_has_permissions'))->updateOrInsert(
                    [
                        'permission_id' => $newPermission->getKey(),
                        'role_id' => $pivot->role_id,
                    ],
                    []
                );
            });

        DB::table(config('permission.table_names.model_has_permissions'))
            ->where('permission_id', $legacyPermission->getKey())
            ->get()
            ->each(function (object $pivot) use ($newPermission): void {
                DB::table(config('permission.table_names.model_has_permissions'))->updateOrInsert(
                    [
                        'permission_id' => $newPermission->getKey(),
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

    public function down(): void
    {
        Permission::query()->firstOrCreate([
            'name' => 'View:AcolhidoGalleryPortal',
            'guard_name' => 'web',
        ]);
    }
};
