<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    private const GUARD = 'web';
    private const SUPER_ADMIN_EMAIL = 'suportenatanmelo@gmail.com';

    /**
     * @var array<int, string>
     */
    private const RESOURCE_SUBJECTS = [
        'Acolhido',
        'AcolhidoGaleria',
        'AcolhidoVideo',
        'ArquivosDiario',
        'AtividadeDesenvolvida',
        'AvaliacaoPessoal',
        'Block',
        'CarteiraAcolhido',
        'DemandaAcolhido',
        'EmpresaParceira',
        'GeradorAtividade',
        'Menu',
        'Media',
        'Page',
        'Seo',
        'FrenteTrabalho',
        'Home',
        'MovimentacaoFinanceira',
        'ProntuarioEvolucao',
        'Reuniao',
        'Role',
        'Saude',
        'SubstanciaPsicoativas',
        'User',
    ];

    /**
     * @var array<int, string>
     */
    private const RESOURCE_ACTIONS = [
        'ViewAny',
        'View',
        'Create',
        'Update',
        'Delete',
        'DeleteAny',
        'Restore',
        'ForceDelete',
        'ForceDeleteAny',
        'RestoreAny',
        'Replicate',
        'Reorder',
    ];

    /**
     * @var array<int, string>
     */
    private const CUSTOM_PERMISSIONS = [
        'View:Dashboard',
        'View:Widgets',
        'View:AcolhidosCadastrosChart',
        'View:Chatify',
        'Create:Chatify',
        'View:FeedbackFamiliar',
        'View:Financeiro',
        'Create:Financeiro',
        'Update:Financeiro',
        'Delete:Financeiro',
        'Approve:Financeiro',
        'Withdraw:Financeiro',
        'Reports:Financeiro',
        'View:ExtratoFinanceiro',
        'Export:ExtratoFinanceiro',
        'Pdf:ExtratoFinanceiro',
        'Print:ExtratoFinanceiro',
        'Admin:ExtratoFinanceiro',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = $this->permissions();

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, self::GUARD);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');

        $superAdminRole = Role::findOrCreate($superAdminRoleName, self::GUARD);
        $superAdminRole->syncPermissions($permissions);

        Role::findOrCreate('admin', self::GUARD)
            ->syncPermissions($permissions);

        Role::findOrCreate(config('filament-shield.panel_user.name', 'panel_user'), self::GUARD)
            ->syncPermissions([
                'View:FeedbackFamiliar',
            ]);

        User::role($superAdminRoleName, self::GUARD)
            ->where('email', '!=', self::SUPER_ADMIN_EMAIL)
            ->get()
            ->each(fn (User $user): bool => $user->removeRole($superAdminRoleName));

        $superAdmin = User::updateOrCreate(
            ['email' => self::SUPER_ADMIN_EMAIL],
            [
                'name' => 'Suporte Natan Melo',
                'password' => Hash::make('@#Insidesenha22'),
                'email_verified_at' => now(),
            ],
        );

        $superAdmin->assignRole($superAdminRole);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return array<int, string>
     */
    private function permissions(): array
    {
        $permissions = self::CUSTOM_PERMISSIONS;

        foreach (self::RESOURCE_SUBJECTS as $subject) {
            foreach (self::RESOURCE_ACTIONS as $action) {
                $permissions[] = "{$action}:{$subject}";
            }
        }

        return array_values(array_unique($permissions));
    }
}
