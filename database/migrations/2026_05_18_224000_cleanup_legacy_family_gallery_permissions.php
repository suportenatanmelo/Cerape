<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'View:FamilyGallery',
            'View:FamilyDashboardGalleryWidget',
            'View:AcolhidoGalleryPortal',
        ] as $permissionName) {
            $permission = Permission::query()->where('name', $permissionName)->first();

            if (! $permission) {
                continue;
            }

            DB::table(config('permission.table_names.role_has_permissions'))
                ->where('permission_id', $permission->getKey())
                ->delete();

            DB::table(config('permission.table_names.model_has_permissions'))
                ->where('permission_id', $permission->getKey())
                ->delete();

            $permission->delete();
        }
    }

    public function down(): void
    {
        //
    }
};
