<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Administrateur',
            'matricule' => 'ADMIN001',
            'email'     => 'admin@residence.dz',
            'password'  => Hash::make('admin1234'),
            'role'      => 'admin',
        ]);
    }
}