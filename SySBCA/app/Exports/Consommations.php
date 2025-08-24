<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ConsommationsExport implements FromCollection, WithHeadings
{
    protected $consommations;

    public function __construct($consommations)
    {
        $this->consommations = $consommations;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // dd($this->consommations); // Retiré pour la production
        
        return collect($this->consommations)->map(function ($row) {
            
            // Fonction helper pour convertir les valeurs vides en 0
            $toNumber = function($value) {
                return is_numeric($value) ? (float)$value : 0;
            };
            
            // Récupération du nom du médicament avec plusieurs fallbacks
            $nomMedicament = '';
            if (isset($row['medicament'])) {
                if (is_array($row['medicament'])) {
                    $nomMedicament = $row['medicament']['nom'] ?? 
                                   $row['medicament']['name'] ?? 
                                   $row['medicament']['libelle'] ?? '';
                } elseif (is_object($row['medicament'])) {
                    $nomMedicament = $row['medicament']->nom ?? 
                                   $row['medicament']->name ?? 
                                   $row['medicament']->libelle ?? '';
                }
            }
            
            // Si le nom n'est toujours pas trouvé, essayer d'autres champs
            if (empty($nomMedicament)) {
                $nomMedicament = $row['nom_medicament'] ?? 
                               $row['medicament_nom'] ?? 
                               $row['nom'] ?? 
                               'Nom non disponible';
            }

            // Conversion des valeurs numériques avec gestion des chaînes vides
            $qteEnStock = $toNumber($row['qte_en_stock'] ?? 0);
            $qteUtilisee = $toNumber($row['qte_utilisee'] ?? 0);
            $perimee = $toNumber($row['perimee'] ?? 0);
            $perteAvarie = $toNumber($row['perte_avarie'] ?? 0);
            $qteRetourCameg = $toNumber($row['qte_retour_cameg'] ?? 0);
            $stockFinPrecedent = $toNumber($row['stock_fin_precedent'] ?? 0);

            // Écart calculé : différence entre stock précédent et mouvements du trimestre
            $ecartCalcule = $qteEnStock + $qteUtilisee + $perimee + $perteAvarie + $qteRetourCameg - $stockFinPrecedent;

            return [
                'Médicament'             => $nomMedicament,
                'Qté Début Période'      => $toNumber($row['qte_dispo_deb_periode'] ?? 0),
                'Qté Reçue'              => $toNumber($row['qte_recu'] ?? 0),
                'Qté en Stock'           => $qteEnStock,
                'Qté Utilisée'           => $qteUtilisee,
                'Nb Bénéficiaire'        => $toNumber($row['nb_beneficiaire'] ?? 0),
                'Périmée'                => $perimee,
                'Perte/Avarie'           => $perteAvarie,
                'Retour CAMEG'           => $qteRetourCameg,
                'Nb Jours Rupture'       => $toNumber($row['nb_jour_rupture'] ?? 0),
                'Qté Restante'           => $toNumber($row['qte_restante'] ?? 0),
                'Stock Sécurité'         => $toNumber($row['stock_securite'] ?? 0),
                'CMMA'                   => $toNumber($row['cmma'] ?? 0),
                'Cmd Trim Svt'           => $toNumber($row['cmd_trim_svt'] ?? 0),
                'Qté Accordée'           => $row['qte_accordee'] ?? '',  // Garde comme string si c'est du texte
                'Écart en Stock'         => $toNumber($row['ecart_stock'] ?? 0),
                'Écart Calculé'          => $ecartCalcule,
                'Type Écart'             => $row['type_ecart'] ?? '',
                'Libellé Écart'          => $row['libelle_ecart'] ?? '',
            ];
        });
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
            'Nb Jours Rupture',
            'Qté Restante',
            'Stock Sécurité',
            'CMMA',
            'Cmd Trim Svt',
            'Qté Accordée',
            'Écart Stock fin passé et deb trim',
            'Écart Calculé pour ce trimestre',
            'Type Écart',
            'Libellé Écart',
        ];
    }
}