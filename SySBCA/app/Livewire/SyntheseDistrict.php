<?php

namespace App\Livewire;

use App\Models\Consommation;
use App\Models\ConsommationMedicament;
use App\Models\District;
use App\Models\FormationSanitaire;
use Livewire\Component;
use App\Models\Periode;

class SyntheseDistrict extends Component
{
    public $periode;
    public $periodes_all;
    public $synthese_district = [];
    public $conso_ids = [];
    public $conso = [];
    public $conso_asc = [];
    public $type_synthese = 'FS';
    public $user;
    public $cardsContent;
    public $fs = [];
    public $districts = [];
    public $district_actuelle;
    public $districts_search;
    public $district_info;
    public $periode_actuelle;
    public $periode_search;
    public $periode_info;
    public $currentSlide = 0;
    

    public function nextSlide()
    {
        $visibleCards = count($this->synthese_district); 
        if ($this->currentSlide < $visibleCards - 1) {
            $this->currentSlide++;
        }
    }

    public function previousSlide()
    {
        if ($this->currentSlide > 0) {
            $this->currentSlide--;
        }
    }

    // Nouvelle méthode pour aller directement à un slide
    public function goToSlide($index)
    {
        $visibleCards = count($this->synthese_district);
        if ($index >= 0 && $index < $visibleCards) {
            $this->currentSlide = $index;
        }
    }

    public function render()
    {
        $visibleCards = collect($this->synthese_district);
        return view(
            'livewire.synthese-district',
            ['visibleCards' => $visibleCards]
        );
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->chargerDistricts();
        $this->chargerFs();
        $this->determinerPeriodeActuelle();
        $this->rechercherSynthese();
    }

    public function determinerPeriodeActuelle()
    {
        $mois = now()->month;
        $year = now()->year;
        $periode = ceil($mois / 3);
        switch ($periode) {
            case 1:
                $this->periode = 'T1 ' . $year;
                break;
            case 2:
                $this->periode = 'T2 ' . $year;
                break;
            case 3:
                $this->periode = 'T3 ' . $year;
                break;
            case 4:
                $this->periode = 'T4 ' . $year;
                break;
            default:
                $this->periode = 'Non définie';
        }
        $periodes = Periode::orderByDesc('id')->get();
        $index = $periodes->search(function ($periode) {
            return $periode->nom === $this->periode;
        });
        if ($index !== false) {
            $this->periodes_all = $periodes->slice($index)->values();
        } else {
            $this->periodes_all = Periode::all();
        }
        $this->periode_actuelle = Periode::where('nom', $this->periode)->first();
        $this->periode_search = $this->periode_actuelle->id;
    }

    public function exporterPDF(){
       
    }

    public function chargerFs()
    {
        if ($this->user->role->nom_role == 'District') {
            $this->fs = FormationSanitaire::where('district_id', $this->user->entity_id)->get();
        } elseif ($this->user->role->nom_role == 'Administrateur') {
            $this->fs = FormationSanitaire::where('district_id', $this->districts_search)->get();
        }
    }

    public function chargerDistricts(): void
    {
        $this->districts = District::all();
        $this->district_actuelle = $this->districts->first();
        $this->districts_search = $this->district_actuelle->id;
    }

    public function chargerSyntheseConsommationFsOuAsc()
    {
        $this->chargerFs();
        $fs_ids = $this->fs->pluck('id')->toArray();
        
        if (in_array($this->type_synthese, ['FS', 'ASC'])) {
            $this->conso_ids = Consommation::whereIn('formation_sanitaire_id', $fs_ids)
                ->where('acteur', $this->type_synthese)
                ->where('periode_id', $this->periode_search)
                ->where('etat', 'valide')
                ->pluck('id')->toArray();
        } elseif ($this->type_synthese === 'FS+ASC') {
            $this->conso_ids = Consommation::whereIn('formation_sanitaire_id', $fs_ids)
                ->where('periode_id', $this->periode_search)
                ->where('etat', 'valide')
                ->pluck('id')->toArray();
        }

        // CORRECTION PRINCIPALE : Charger les relations medicament
        $this->synthese_district = ConsommationMedicament::selectRaw('medicament_id, 
                                                                      SUM(qte_dispo_deb_periode) as qte_dispo_deb_periode,
                                                                      SUM(qte_recu) as qte_recu,
                                                                      SUM(qte_en_stock) as qte_en_stock,
                                                                      SUM(qte_utilisee) as qte_utilisee,
                                                                      SUM(nb_beneficiaire) as nb_beneficiaire,
                                                                      SUM(perimee) as perimee,
                                                                      SUM(perte_avarie) as perte_avarie,
                                                                      SUM(qte_retour_cameg) as qte_retour_cameg,
                                                                      SUM(nb_jour_rupture) as nb_jour_rupture,
                                                                      SUM(qte_restante) as qte_restante,
                                                                      SUM(stock_securite) as stock_securite,
                                                                      SUM(cmma) as cmma,
                                                                      SUM(cmd_trim_svt) as cmd_trim_svt,
                                                                      SUM(qte_accordee) as qte_accordee')
            ->with('medicament') // Charger la relation medicament
            ->whereIn('consommation_id', $this->conso_ids)
            ->groupBy('medicament_id')
            ->get()
            ->toArray(); // Convertir en array pour éviter les problèmes de sérialisation

        // Réinitialiser le slide si nécessaire
        if ($this->currentSlide >= count($this->synthese_district)) {
            $this->currentSlide = 0;
        }
    }

    public function rechercherSynthese()
    {
        $this->periode_info = Periode::where('id', $this->periode_search)->first();
        $this->district_info = District::where('id', $this->districts_search)->first();
        $this->chargerSyntheseConsommationFsOuAsc();
        // Réinitialiser le carrousel au premier slide
        $this->currentSlide = 0;
    }
}