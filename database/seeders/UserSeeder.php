<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
    [
        'name'      => 'Admin',
        'email'     => 'admin@residence.dz',
        'password'  => Hash::make('password123'),
        'role'      => 'admin',
        'matricule' => 'ADMIN001',
    ],
    [
        'name'      => 'Resp Hebergement',
        'email'     => 'hebergement@residence.dz',
        'password'  => Hash::make('password123'),
        'role'      => 'resp_hebergement',
        'matricule' => 'HEBERG001',
    ],
    [
        'name'      => 'Technicien',
        'email'     => 'technicien@residence.dz',
        'password'  => Hash::make('password123'),
        'role'      => 'technicien',
        'matricule' => 'TECH001',
    ],
    [
        'name'      => 'Resp Foyer',
        'email'     => 'foyer@residence.dz',
        'password'  => Hash::make('password123'),
        'role'      => 'resp_foyer',
        'matricule' => 'FOYER001',
    ],
    [
        'name'      => 'Etudiante Test',
        'email'     => 'etudiante@residence.dz',
        'password'  => Hash::make('password123'),
        'role'      => 'etudiante',
        'matricule' => 'ETU001',
    ],
];

        foreach ($users as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}