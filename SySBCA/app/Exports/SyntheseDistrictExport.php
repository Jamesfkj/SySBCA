<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SyntheseDistrictExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return collect($this->data)->map(function ($row) {
            return [
                'medicament_nom'   => $row['medicament']['nom'] ?? '', // sécurité si jamais medicament est null
                'qte_debut'        => $row['qte_dispo_deb_periode'] ?? '',
                'qte_recue'        => $row['qte_recu'] ?? '',
                'qte_stock'        => $row['qte_en_stock'] ?? '',
                'qte_utilisee'     => $row['qte_utilisee'] ?? '',
                'nb_beneficiaire'  => $row['nb_beneficiaire'] ?? '',
                'perimee'          => $row['perimee'] ?? '',
                'perte_avarie'     => $row['perte_avarie'] ?? '',
                'retour_cameg'     => $row['qte_retour_cameg'] ?? '',
                'nb_jours_rupture' => $row['nb_jour_rupture'] ?? '',
                'qte_restante'     => $row['qte_restante'] ?? '',
                'stock_securite'   => $row['stock_securite'] ?? '',
                'cmma'             => $row['cmma'] ?? '',
                'cmd_trim_svt'     => $row['cmd_trim_svt'] ?? '',
                'qte_accordee'     => $row['qte_accordee'] ?? '',
            ];
        })->toArray();
    }
    public function headings(): array
    {
        return [
            'Médicament',
            'Qté Début Période',
            'Qté Reçue',
            'Qté en Stock',
            'Qté Utilisée',
            'Nb Bénéficiaire',
            'Périmée',
            'Perte/Avarie',
            'Retour CAMEG',
            'Nb jours Rupture',
            'Qté Restante',
            'Stock Sécurité',
            'CMMA',
            'Cmd Trim Svt',
            'Qté Accordée'
        ];
    }
}
