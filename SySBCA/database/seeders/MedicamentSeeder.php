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
                'composition' => '12 comp/pl',
                'conditionnement' => 'Boîte',
                'qte_par_conditionnement' => 12,
                'format' => 'Plaquette',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Amodiaquine + Artesunate',
                'code' => 'ASAQ',
                'composition' => '6 comp/pl',
                'conditionnement' => 'Boîte',
                'qte_par_conditionnement' => 6,
                'format' => 'Plaquette',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Chloroquine',
                'code' => 'CQ',
                'composition' => '30 comp/pl',
                'conditionnement' => 'Boîte',
                'qte_par_conditionnement' => 30,
                'format' => 'Plaquette',
                'fs_only' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Quinine',
                'code' => 'QN',
                'composition' => '6 comp/pl',
                'conditionnement' => 'Boîte',
                'qte_par_conditionnement' => 6,
                'format' => 'Plaquette',
                'fs_only' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Sulfadoxine + Pyrimethamine',
                'code' => 'SP',
                'composition' => '3 comp/pl',
                'conditionnement' => 'Boîte',
                'qte_par_conditionnement' => 3,
                'format' => 'Plaquette',
                'fs_only' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    
    }
}
