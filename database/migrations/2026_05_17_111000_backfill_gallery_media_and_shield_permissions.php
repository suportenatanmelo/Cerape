<?php

use App\Models\AcolhidoGaleria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $this->backfillGalleryMedia();
        $this->syncLegacyPermissionsToCanonicalAliases();
    }

    public function down(): void
    {
        //
    }

    private function backfillGalleryMedia(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('media')) {
            return;
        }

        AcolhidoGaleria::query()
            ->get()
            ->each(function (AcolhidoGaleria $galeria): void {
                if ($galeria->getMedia('gallery')->isNotEmpty()) {
                    return;
                }

                foreach (array_values(array_filter($galeria->imagens ?? [])) as $path) {
                    try {
                        $galeria
                            ->addMediaFromDisk($path, 'public')
                            ->toMediaCollection('gallery');
                    } catch (Throwable) {
                        // Keep legacy JSON paths untouched when a file is missing.
                    }
                }
            });
    }

    private function syncLegacyPermissionsToCanonicalAliases(): void
    {
        $permissions = Permission::query()->get();

        foreach ($permissions as $permission) {
            $canonicalName = $this->canonicalPermissionName($permission->name);

            if ($canonicalName === null || $canonicalName === $permission->name) {
                continue;
            }

            $canonicalPermission = Permission::query()->firstOrCreate([
                'name' => $canonicalName,
                'guard_name' => $permission->guard_name,
            ]);

            DB::table(config('permission.table_names.role_has_permissions'))
                ->where('permission_id', $permission->getKey())
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
                ->where('permission_id', $permission->getKey())
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
        }
    }

    private function canonicalPermissionName(string $permissionName): ?string
    {
        if (! str_contains($permissionName, ':')) {
            return null;
        }

        [$ability, $subject] = explode(':', $permissionName, 2);

        return Str::studly($ability).':'.str_replace('_', '', Str::studly($subject));
    }
};
