<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'suportenatanmelo@gmail.com'],
            User::factory()->make([
                'name' => 'Suporte Natan Melo',
                'email' => 'suportenatanmelo@gmail.com',
                'password' => Hash::make('insidesenha22'),
            ])->toArray(),
        );

        Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->assignRole('super_admin');

        $this->call([
            AcolhidoSeeder::class,
            AvaliacaoPessoalSeeder::class,
        ]);
    }
}
