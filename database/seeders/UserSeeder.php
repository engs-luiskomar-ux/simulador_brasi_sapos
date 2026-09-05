<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@brasileirao.test',
                'role' => UserRole::ADMIN,
            ],
            [
                'name' => 'Organizador',
                'email' => 'organizador@brasileirao.test',
                'role' => UserRole::ORGANIZADOR,
            ],
            [
                'name' => 'Torcedor',
                'email' => 'torcedor@brasileirao.test',
                'role' => UserRole::TORCEDOR,
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create([
                ...$usuario,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
