<?php

namespace App\Livewire;

use App\Models\Consommation;
use App\Models\FormationSanitaire;
use App\Models\Periode;
use App\Models\District;
use App\Models\Region;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $periode_search;
    public $fs_search = '';
    public $region_search;
    public $district_search;
    public $fs_submit;
    public $asc_submit;
    public $user;
    public $periode;
    public $consommations_fs;
    public $consommations_fs_total;
    public $consommations_asc_total;
    public $consommations_asc;
    public $periode_actuelle;
    public $prompt_date;
    public $district_id;
    public $nb_fs = 0;
    public $nb_asc = 0;
    public $nb_fs_soumission = 0;
    public $nb_asc_soumission = 0;
    public $nb_fs_valide = 0;
    public $nb_asc_valide = 0;
    public $fs_prompt = 0;
    public $asc_prompt = 0;
    public $fs_ids = [];
    public $soumissions = [];
    public $validations = [];
    public $topCommandes = [];
    public $fs = [];
    public $districts = [];
    public $regions = [];
    public $periodes_all = [];

    public function mount()
    {
        $this->user = auth()->user();
        $this->chargerPeriode();
        $this->initialiserDonneesSelonRole();
        $this->chargerStatistiques();
        $this->checkSubmit();
    }

    private function initialiserDonneesSelonRole()
    {
        $role = $this->user->role->nom_role;

        switch ($role) {
            case 'Administrateur':
                $this->initialiserAdministrateur();
                break;
            case 'District':
                $this->initialiserDistrict();
                break;
            case 'Formation sanitaire':
                $this->initialiserFormationSanitaire();
                break;
        }
    }

    private function initialiserAdministrateur()
    {
        // Charger toutes les régions, districts et formations sanitaires
        $this->regions = Region::orderBy('nom')->get();
        $this->districts = District::orderBy('nom')->get();
        $this->fs = FormationSanitaire::orderBy('nom')->get();
        
        // Initialiser les données globales par défaut (toutes les FS)
        $this->fs_ids = FormationSanitaire::pluck('id');
        $this->calculerStatistiquesDeBase();
    }

    private function mettreAJourDonneesAdministrateur()
    {
        // Réinitialiser les collections
        $this->districts = collect();
        $this->fs = collect();
        $this->fs_ids = collect();

        if ($this->region_search) {
            // Filtrer les districts par région
            $this->districts = District::where('region_id', $this->region_search)
                ->orderBy('nom')
                ->get();
            
            if ($this->district_search) {
                // Filtrer les FS par district spécifique
                $this->fs = FormationSanitaire::where('district_id', $this->district_search)
                    ->orderBy('nom')
                    ->get();
                $this->fs_ids = $this->fs->pluck('id');
            } else {
                // Toutes les FS de la région sélectionnée
                $districtIds = $this->districts->pluck('id');
                $this->fs = FormationSanitaire::whereIn('district_id', $districtIds)
                    ->orderBy('nom')
                    ->get();
                $this->fs_ids = $this->fs->pluck('id');
            }
        } else {
            // Aucune région sélectionnée = toutes les données
            $this->districts = District::orderBy('nom')->get();
            $this->fs = FormationSanitaire::orderBy('nom')->get();
            $this->fs_ids = $this->fs->pluck('id');
        }

        $this->calculerStatistiquesDeBase();
    }

    private function initialiserDistrict()
    {
        $this->district_id = $this->user->entity_id;
        $this->fs = FormationSanitaire::where('district_id', $this->district_id)
            ->orderBy('nom')
            ->get();
        $this->fs_ids = $this->fs->pluck('id');
        $this->calculerStatistiquesDeBase();
    }

    private function initialiserFormationSanitaire()
    {
        $formationId = $this->user->entity_id;
        $formation = FormationSanitaire::find($formationId);

        if ($formation) {
            $this->district_id = $formation->district_id;
            $this->fs_ids = collect([$formationId]);
            $this->nb_fs = 1;
            $this->nb_asc = $formation->nb_asc ?? 0;
        }
    }

    private function calculerStatistiquesDeBase()
    {
        if ($this->fs_ids->isNotEmpty()) {
            $this->nb_fs = $this->fs_ids->count();
            $this->nb_asc = FormationSanitaire::whereIn('id', $this->fs_ids)->sum('nb_asc');
        } else {
            $this->nb_fs = 0;
            $this->nb_asc = 0;
        }
    }

    public function chargerPeriode()
    {
        $mois = now()->month;
        $year = now()->year;
        $trimestre = ceil($mois / 3);
        $nomPeriode = "T{$trimestre} {$year}";
        $this->periode = $nomPeriode;
        $dernierMoisTrimestre = $trimestre * 3;
        $dernierJourMois = Carbon::createFromDate($year, $dernierMoisTrimestre, 1)->endOfMonth()->day;
        $this->prompt_date = Carbon::createFromDate($year, $dernierMoisTrimestre, $dernierJourMois);
        // Charger toutes les périodes
        $this->periodes_all = Periode::orderByDesc('id')->get();
        
        // Trouver la période actuelle
        $this->periode_actuelle = $this->periodes_all->firstWhere('nom', $nomPeriode)
            ?? $this->periodes_all->first();

        // Initialiser le filtre de période avec la période actuelle
        if (!$this->periode_search) {
            $this->periode_search = $this->periode_actuelle->id;
        }
    }

    public function chargerStatistiques()
    {
        if ($this->fs_ids->isEmpty()) {
            $this->reinitialiserStatistiques();
            return;
        }

        $this->calculerStatistiquesConsommation();
        $this->chargerSoumissions();
        $this->chargerValidations();
        $this->chargerTopCommandes();
    }

    private function reinitialiserStatistiques()
    {
        $this->nb_fs_soumission = 0;
        $this->nb_asc_soumission = 0;
        $this->nb_fs_valide = 0;
        $this->nb_asc_valide = 0;
        $this->fs_prompt = 0;
        $this->asc_prompt = 0;
        $this->soumissions = collect();
        $this->validations = collect();
        $this->topCommandes = collect();
    }

    private function calculerStatistiquesConsommation()
    {
        $periodeId = $this->periode_search ?? $this->periode_actuelle->id;

        // Statistiques ASC
        $this->nb_asc_soumission = $this->compterConsommations('ASC', ['soumis', 'valide'], $periodeId);
        $this->nb_asc_valide = $this->compterConsommations('ASC', ['valide'], $periodeId);
        $this->asc_prompt = $this->compterConsommationsPrompt('ASC', $periodeId);

        // Statistiques FS
        $this->nb_fs_soumission = $this->compterConsommations('FS', ['soumis', 'valide'], $periodeId);
        $this->nb_fs_valide = $this->compterConsommations('FS', ['valide'], $periodeId);
        $this->fs_prompt = $this->compterConsommationsPrompt('FS', $periodeId);
    }

    private function compterConsommations($acteur, $etats, $periodeId)
    {
        $query = Consommation::where('acteur', $acteur)
            ->whereIn('formation_sanitaire_id', $this->fs_ids)
            ->whereIn('etat', $etats)
            ->where('periode_id', $periodeId);

        // Appliquer le filtre formation sanitaire si sélectionné
        if ($this->fs_search) {
            $query->where('formation_sanitaire_id', $this->fs_search);
        }

        return $query->count();
    }

    private function compterConsommationsPrompt($acteur, $periodeId)
    {
        $query = Consommation::where('acteur', $acteur)
            ->whereIn('formation_sanitaire_id', $this->fs_ids)
            ->where('etat', 'soumis')
            ->where('periode_id', $periodeId)
            ->where('submitted_at', '<=', $this->prompt_date);

        if ($this->fs_search) {
            $query->where('formation_sanitaire_id', $this->fs_search);
        }
        return $query->count();
    }

    private function chargerSoumissions()
    {
        $query = Consommation::with(['formationSanitaire', 'periode'])
            ->whereIn('formation_sanitaire_id', $this->fs_ids)
            ->where('etat', 'soumis')
            ->where('periode_id', $this->periode_search ?? $this->periode_actuelle->id);

        // Appliquer le filtre formation sanitaire si sélectionné
        if ($this->fs_search) {
            $query->where('formation_sanitaire_id', $this->fs_search);
        }

        $this->soumissions = $query->orderByDesc('submitted_at')->limit(50)->get();
    }

    private function chargerValidations()
    {
        $query = Consommation::with(['formationSanitaire', 'periode'])
            ->whereIn('formation_sanitaire_id', $this->fs_ids)
            ->where('etat', 'valide')
            ->where('periode_id', $this->periode_search ?? $this->periode_actuelle->id);

        // Appliquer le filtre formation sanitaire si sélectionné
        if ($this->fs_search) {
            $query->where('formation_sanitaire_id', $this->fs_search);
        }

        $this->validations = $query->orderByDesc('updated_at')->limit(50)->get();
    }

    private function chargerTopCommandes()
    {
        $query = DB::table('consommation_medicament as cm')
            ->join('consommations as c', 'c.id', '=', 'cm.consommation_id')
            ->join('formations_sanitaires as fs', 'fs.id', '=', 'c.formation_sanitaire_id')
            ->join('medicaments as m', 'm.id', '=', 'cm.medicament_id')
            ->select('m.nom', DB::raw('SUM(cm.cmd_trim_svt) as total_commande'))
            ->whereIn('c.formation_sanitaire_id', $this->fs_ids)
            ->where('c.periode_id', $this->periode_search ?? $this->periode_actuelle->id);

        // Appliquer le filtre formation sanitaire si sélectionné
        if ($this->fs_search) {
            $query->where('c.formation_sanitaire_id', $this->fs_search);
        }

        $this->topCommandes = $query
            ->groupBy('m.id', 'm.nom')
            ->orderByDesc('total_commande')
            ->limit(10)
            ->get();
    }

    public function chercherStatistiques()
    {
        // Mettre à jour les données selon le rôle
        if ($this->user->role->nom_role === 'Administrateur') {
            $this->mettreAJourDonneesAdministrateur();
        }

        // Recharger toutes les statistiques
        $this->chargerStatistiques();
    }

    public function updatedRegionSearch()
    {
        if ($this->user->role->nom_role === 'Administrateur') {
            $this->district_search = null;
            $this->fs_search = '';
            $this->chercherStatistiques();
        }
    }

    public function updatedDistrictSearch()
    {
        if ($this->user->role->nom_role === 'Administrateur') {
            $this->fs_search = '';
            $this->chercherStatistiques();
        }
    }

    public function resetFiltres()
    {
        $this->periode_search = $this->periode_actuelle->id;
        $this->fs_search = '';

        if ($this->user->role->nom_role === 'Administrateur') {
            $this->region_search = null;
            $this->district_search = null;
        }

        $this->chercherStatistiques();
    }

    public function exporterDonnees($type = 'soumissions')
    {
        session()->flash('message', "Export des {$type} en cours...");
    }

    public function getPourcentageCompletude($type = 'fs')
    {
        if ($this->nb_fs == 0) return 0;

        $soumissions = $type === 'fs' ? $this->nb_fs_soumission : $this->nb_asc_soumission;
        return intval(($soumissions / $this->nb_fs) * 100);
    }

    public function getPourcentagePromptitude($type = 'fs')
    {
        if ($this->nb_fs == 0) return 0;

        $prompt = $type === 'fs' ? $this->fs_prompt : $this->asc_prompt;
        return intval(($prompt / $this->nb_fs) * 100);
    }

    public function chargerConsommations()
    {
        $user = auth()->user();
        if ($user->role->nom_role === 'Formation sanitaire') {
            $formationId = $user->entity_id;
            $consommations = Consommation::where('formation_sanitaire_id', $formationId)->get();
            $this->consommations_fs = $consommations->where('acteur', 'FS');
            $this->consommations_asc = $consommations->where('acteur', 'ASC');
            $this->consommations_fs_total = $this->consommations_fs->count();
            $this->consommations_asc_total = $this->consommations_asc->count();
        } elseif ($user->role->nom_role === 'District') {
            $districtId = $user->entity_id;
            $this->nb_fs = FormationSanitaire::where('district_id', $districtId)->count();
            $this->nb_asc = FormationSanitaire::where('district_id', $districtId)->sum('nb_asc');
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function checkSubmit()
    {
        $user = $this->user;
        $this->chargerConsommations();
        if ($user->role->nom_role === 'Formation sanitaire') {
            $periode = Periode::where('nom', $this->periode)->first();
            $check_fs = $this->consommations_fs->where('periode_id', $periode->id)->count();
            $check_asc = $this->consommations_asc->where('periode_id', $periode->id)->count();
            $this->fs_submit = $check_fs > 0;
            $this->asc_submit = $check_asc > 0;
        }
    }
}