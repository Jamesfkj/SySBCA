<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    try {
        $date = now();
        $year = $date->year;
        $nextYear = $year + 1;

        DB::table('periodes')->insert([
            [
                'nom' => 'T1 ' . $nextYear,
                'mois_debut' => 'Janvier',
                'mois_fin' => 'Mars',
                'annee' => $nextYear,
                'etat' => 'inactif',
            ],
            [
                'nom' => 'T2 ' . $nextYear,
                'mois_debut' => 'Avril',
                'mois_fin' => 'Juin',
                'annee' => $nextYear,
                'etat' => 'inactif',
            ],
            [
                'nom' => 'T3 ' . $nextYear,
                'mois_debut' => 'Juillet',
                'mois_fin' => 'Septembre',
                'annee' => $nextYear,
                'etat' => 'inactif',
            ],
            [
                'nom' => 'T4 ' . $nextYear,
                'mois_debut' => 'Octobre',
                'mois_fin' => 'Décembre',
                'annee' => $nextYear,
                'etat' => 'inactif',
            ],
        ]);

        Log::info("Insertion réussie des périodes pour $nextYear.");

    } catch (\Exception $e) {
        Log::error('Erreur insertion périodes: ' . $e->getMessage());
    }
})->yearlyOn(7, 15, '12:08');