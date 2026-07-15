<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * @return array<int, string>
     */
    private function permissions(): array
    {
        return [
            'ViewAny:GeradorAtividade',
            'View:GeradorAtividade',
            'Create:GeradorAtividade',
            'Update:GeradorAtividade',
            'Delete:GeradorAtividade',
            'DeleteAny:GeradorAtividade',
            'Restore:GeradorAtividade',
            'RestoreAny:GeradorAtividade',
            'ForceDelete:GeradorAtividade',
            'ForceDeleteAny:GeradorAtividade',
            'Replicate:GeradorAtividade',
            'Reorder:GeradorAtividade',
        ];
    }

    public function up(): void
    {
        $guardName = config('auth.defaults.guard', 'web');
        $permissionIds = [];

        foreach ($this->permissions() as $permissionName) {
            $permission = Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guardName,
            ]);

            $permissionIds[] = $permission->getKey();
        }

        $superAdminRole = Role::query()->where('name', 'super_admin')->where('guard_name', $guardName)->first();

        if (! $superAdminRole) {
            return;
        }

        foreach ($permissionIds as $permissionId) {
            DB::table(config('permission.table_names.role_has_permissions'))
                ->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $superAdminRole->getKey(),
                ]);
        }
    }

    public function down(): void
    {
        $guardName = config('auth.defaults.guard', 'web');
        $permissions = Permission::query()
            ->where('guard_name', $guardName)
            ->whereIn('name', $this->permissions())
            ->get();

        foreach ($permissions as $permission) {
            DB::table(config('permission.table_names.role_has_permissions'))
                ->where('permission_id', $permission->getKey())
                ->delete();

            DB::table(config('permission.table_names.model_has_permissions'))
                ->where('permission_id', $permission->getKey())
                ->delete();

            $permission->delete();
        }
    }
};
