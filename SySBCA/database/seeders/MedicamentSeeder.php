<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medicaments')->insert([
            [
                'nom' => 'Artemether + Lumefantrine',
                'code' => 'AL',
                'composition' => 'Artemether 20mg + Lumefantrine 120mg',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Amodiaquine + Artesunate',
                'code' => 'ASAQ',
                'composition' => 'Amodiaquine 150mg + Artesunate 50mg',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Chloroquine',
                'code' => 'CQ',
                'composition' => 'Chloroquine phosphate 250mg',
                'fs_only' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Quinine',
                'code' => 'QN',
                'composition' => 'Quinine sulfate 300mg',
                'fs_only' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Sulfadoxine + Pyrimethamine',
                'code' => 'SP',
                'composition' => 'Sulfadoxine 500mg + Pyrimethamine 25mg',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
