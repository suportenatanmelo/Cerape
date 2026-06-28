<?php

use App\Models\AcolhidoGaleria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('curator')) {
            return;
        }

        Schema::table('curator', function (Blueprint $table): void {
            if (! Schema::hasColumn('curator', 'acolhido_id')) {
                $table->foreignId('acolhido_id')->nullable()->after('tenant_id')->constrained('acolhidos')->nullOnDelete();
            }
        });

        $this->syncShieldPermissions();
        $this->backfillCuratorFromFamilyGallery();
    }

    public function down(): void
    {
        if (! Schema::hasTable('curator')) {
            return;
        }

        Schema::table('curator', function (Blueprint $table): void {
            if (Schema::hasColumn('curator', 'acolhido_id')) {
                $table->dropConstrainedForeignId('acolhido_id');
            }
        });
    }

    private function syncShieldPermissions(): void
    {
        $guard = config('auth.defaults.guard', 'web');
        $resourcePermissions = [
            'ViewAny:Media',
            'View:Media',
            'Create:Media',
            'Update:Media',
            'Delete:Media',
            'DeleteAny:Media',
            'Restore:Media',
            'RestoreAny:Media',
            'ForceDelete:Media',
            'ForceDeleteAny:Media',
            'Replicate:Media',
            'Reorder:Media',
        ];

        foreach ($resourcePermissions as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guard,
            ]);
        }

        $legacyPermission = Permission::query()->where('name', 'View:FamilyGallery')->first();
        $newPermission = Permission::query()->where('name', 'ViewAny:Media')->first();

        if (! $legacyPermission || ! $newPermission) {
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
    }

    private function backfillCuratorFromFamilyGallery(): void
    {
        if (! Schema::hasTable('media')) {
            return;
        }

        AcolhidoGaleria::query()
            ->with('acolhido')
            ->get()
            ->each(function (AcolhidoGaleria $galeria): void {
                $acolhidoId = $galeria->acolhido_id;

                foreach ($galeria->getMedia('gallery') as $media) {
                    $path = $media->getPathRelativeToRoot();
                    $directory = trim(pathinfo($path, PATHINFO_DIRNAME), './\\');

                    $payload = [
                        'disk' => $media->disk ?: 'public',
                        'directory' => $directory !== '' ? $directory : null,
                        'visibility' => 'public',
                        'name' => pathinfo($media->file_name, PATHINFO_FILENAME),
                        'path' => $path,
                        'width' => $media->getCustomProperty('width'),
                        'height' => $media->getCustomProperty('height'),
                        'size' => $media->size,
                        'type' => $media->mime_type ?: 'image/jpeg',
                        'ext' => pathinfo($media->file_name, PATHINFO_EXTENSION),
                        'title' => $galeria->titulo,
                        'description' => $galeria->descricao,
                        'pretty_name' => $media->name ?: $media->file_name,
                        'acolhido_id' => $acolhidoId,
                        'created_at' => $media->created_at ?? now(),
                        'updated_at' => $media->updated_at ?? now(),
                    ];

                    $existingId = DB::table('curator')->where('path', $path)->value('id');

                    if ($existingId) {
                        DB::table('curator')
                            ->where('id', $existingId)
                            ->update([
                                'acolhido_id' => $acolhidoId,
                                'updated_at' => now(),
                            ]);

                        continue;
                    }

                    unset($payload['pretty_name']);

                    DB::table('curator')->insert($payload);
                }
            });
    }
};
