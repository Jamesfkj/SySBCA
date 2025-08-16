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
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Médicament ID',
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
