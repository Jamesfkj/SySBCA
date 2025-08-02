<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('utilisateurs')->insert([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('12345678'),
            'etat' => 'actif',
            'role_id' => 1, 
            'entity_id' => null,
            'entity_type' => null,
            'doit_renitialiser_pwd' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
