<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte Admin
        User::create([
            'name'      => 'Administrateur',
            'matricule' => 'ADMIN001',
            'email'     => 'admin@residence.dz',
            'role'      => 'admin',
            'phone'     => '0550000000',
            'password'  => Hash::make('Admin@1234'),
        ]);

        // Compte Responsable Hébergement
        User::create([
            'name'      => 'Resp Hebergement',
            'matricule' => 'RHEB001',
            'email'     => 'hebergement@residence.dz',
            'role'      => 'resp_hebergement',
            'phone'     => '0550000001',
            'password'  => Hash::make('Rheb@1234'),
        ]);

        // Compte Technicien
        User::create([
            'name'      => 'Technicien',
            'matricule' => 'TECH001',
            'email'     => 'technicien@residence.dz',
            'role'      => 'technicien',
            'phone'     => '0550000002',
            'password'  => Hash::make('Tech@1234'),
        ]);

        // Compte Responsable Foyer
        User::create([
            'name'      => 'Resp Foyer',
            'matricule' => 'RFOY001',
            'email'     => 'foyer@residence.dz',
            'role'      => 'resp_foyer',
            'phone'     => '0550000003',
            'password'  => Hash::make('Foyer@1234'),
        ]);

        // Compte Etudiante test
        User::create([
            'name'      => 'Etudiante Test',
            'matricule' => 'ETU001',
            'email'     => 'etudiante@residence.dz',
            'role'      => 'etudiante',
            'phone'     => '0550000004',
            'password'  => Hash::make('Etu@1234'),
        ]);
    }
}