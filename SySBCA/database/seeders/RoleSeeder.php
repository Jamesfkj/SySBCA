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
            ['nom_role' => 'Administrateur'],
            ['nom_role' => 'Region'],
            ['nom_role' => 'District'],
            ['nom_role' => 'Formation sanitaire'],
        ]);
    }
}
