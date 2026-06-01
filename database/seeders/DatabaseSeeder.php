<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdmin = User::updateOrCreate(
            ['email' => 'suportenatanmelo@gmail.com'],
            [
                'name' => 'Suporte Natan Melo',
                'password' => Hash::make('insidesenha22'),
                'email_verified_at' => now(),
            ],
        );

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->assignRole($superAdminRole);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

    }
}