<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('roles')->insert([
            ['nom_role' => 'admin'],
            ['nom_role' => 'region'],
            ['nom_role' => 'district'],
            ['nom_role' => 'fs'],//qui désigne formation sanitaire
        ]);
    }
}
