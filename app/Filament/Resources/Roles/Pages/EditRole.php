<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Support\ActivityLogger;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Override;

class EditRole extends EditRecord
{
    public Collection $permissions;

    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    #[Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect(RoleResource::getPermissionStateKeys())
            ->flatMap(fn (string $key): array => Arr::wrap($data[$key] ?? []))
            ->filter(fn (mixed $permission): bool => filled($permission))
            ->values()
            ->flatten()
            ->unique();

        if (Utils::isTenancyEnabled() && Arr::has($data, Utils::getTenantModelForeignKey()) && filled($data[Utils::getTenantModelForeignKey()])) {
            return Arr::only($data, ['name', 'guard_name', Utils::getTenantModelForeignKey()]);
        }

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function (string $permission) use ($permissionModels): void {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $beforePermissions = $this->record->permissions()->pluck('name')->values()->all();
        $afterPermissions = $permissionModels->pluck('name')->values()->all();

        $this->record->syncPermissions($permissionModels);

        if ($beforePermissions !== $afterPermissions) {
            app(ActivityLogger::class)->custom(
                'Usuários',
                'update',
                'Alterou permissões do perfil ' . $this->record->name,
                $this->record,
                ['permissions' => $beforePermissions],
                ['permissions' => $afterPermissions],
            );
        }
    }
}