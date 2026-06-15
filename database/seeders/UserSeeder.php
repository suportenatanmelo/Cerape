<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'suportenatanmelo@gmail.com'],
            [
                'name' => 'Suporte Natan Melo',
                'password' => Hash::make('@#Insidesenha22'),
                'email_verified_at' => now(),
            ]
        );
    }
}
