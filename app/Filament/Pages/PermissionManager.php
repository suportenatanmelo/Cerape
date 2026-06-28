<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;

class PermissionManager extends Page
{
    protected string $view = 'filament.pages.permission-manager';

    protected static string|UnitEnum|null $navigationGroup = 'Controle de acesso';

    protected static ?string $navigationLabel = 'Gerenciar permissões';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    public ?string $role = null;

    public array $permissions = [];

  public function mount(): void
{
    $this->permissions = [];

    $this->form->fill([
        'permissions' => [],
    ]);
}

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('role')
                ->label('Papel')
                ->options(fn () => Role::query()->pluck('name', 'name'))
                ->live()
                ->afterStateUpdated(fn () => $this->loadPermissions()),

            Section::make('Permissões')
                ->schema([
  Forms\Components\CheckboxList::make('permissions')
    ->label('Controle de Acesso')
    ->options(
    Permission::pluck('name', 'id')->toArray()
)
    ->default([])
    ->afterStateHydrated(function ($state) {
        return $state ?? [];
    })
    ->dehydrateStateUsing(fn ($state) => $state ?? [])
    ->columns(3),
                ]),
        ]);
    }

    protected function getGroupedPermissions(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($permission) => explode('_', $permission->name)[1] ?? 'geral')
            ->mapWithKeys(function ($group, $key) {
                return [
                    strtoupper($key) => $group->pluck('name', 'name')->toArray(),
                ];
            })
            ->toArray();
    }

  public function loadPermissions(): void
{
    if (!$this->role) {
        $this->permissions = [];
        return;
    }

    $role = Role::findByName($this->role);

    $this->permissions = $role?->permissions?->pluck('name')?->toArray() ?? [];

    $this->form->fill([
        'role' => $this->role,
        'permissions' => $this->permissions,
    ]);
}

    public function save(): void
    {
        if (!$this->role) {
            return;
        }

        $role = Role::findByName($this->role);

$permissions = Permission::whereIn('id', $this->permissions)->get();

$role->syncPermissions($permissions);

        Notification::make()
            ->title('Permissões atualizadas com sucesso!')
            ->success()
            ->send();
    }
}
