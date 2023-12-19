<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nom' => ' Doe',
            'prenom' => 'John',
            'email' => 'john@example.com',
            'password' => Hash::make('123456'), // Utilisation de la fonction Hash pour crypter le mot de passe
            'roles'=>'admin',
        ]);
    }
}
