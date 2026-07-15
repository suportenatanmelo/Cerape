<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FilamentShieldManualSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('permissions')) {
            $this->command->warn('Table `permissions` does not exist. Skipping FilamentShieldManualSeeder.');
            return;
        }

        $permissions = [
            // Auditoria
            'View:Auditoria',
            'ViewAny:Auditoria',
            // Gerenciar permissões
            'View:Gerenciar permissões',
            'ViewAny:Gerenciar permissões',
        ];

        foreach ($permissions as $name) {
            DB::table('permissions')->updateOrInsert([
                'name' => $name,
                'guard_name' => 'web',
            ], [
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        $this->command->info('FilamentShieldManualSeeder: permissions ensured.');
    }
}
