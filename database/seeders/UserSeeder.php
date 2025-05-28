<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Colaborador',
                'email' => 'colaborador@mail.com',
                'password' => Hash::make('colaborador#engeselt'),
                'role' => 'colaborador',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Técnico',
                'email' => 'tecnico@mail.com',
                'password' => Hash::make('tecnico#engeselt'),
                'role' => 'tecnico',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::firstOrCreate(
                ['email' => $usuario['email']],
                $usuario
            );
        }
    }
}
