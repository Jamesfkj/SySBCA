<?php

namespace App\Livewire;

use App\Models\Consommation;
use App\Models\FormationSanitaire;
use App\Models\Periode;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $periode;
    public $user;
    public $nb_fs;
    public $nb_asc;
    public $nb_fs_soumission;
    public $nb_asc_soumission;
    public $nb_fs_valide;
    public $nb_asc_valide;
    public $fs_prompt;
    public $asc_prompt;
    public $prompt_date;
    public $district_id;
    public $asc_submit = false;
    public $fs_submit = false;
    public $periode_search;
    public $soumissions = [];
    public $topCommandes = [];
    public $validations = [];
    public $fs = [];
    public $periodes_all = [];
    public $consommations_fs = [];
    public $consommations_fs_total;
    public $consommations_asc = [];
    public $consommations_asc_total;
    public $periode_actuelle;
    public function mount()
    {
        $this->user = auth()->user();
        $this->district_id = $this->user->entity_id;
        $this->fs = FormationSanitaire::where('district_id', $this->district_id)->get();
        $this->chargerPeriode();
        $this->chargerConsommations();
        $this->statistique();
        $this->checkSubmit();
    }
    public function chargerPeriode()
    {
        $mois = now()->month;
        $year = now()->year;
        $periode = ceil($mois / 3);

        switch ($periode) {
            case 1:
                $this->periode = 'T1 ' . $year;
                $this->prompt_date = Carbon::createFromDate($year, 3, 31)->format('d/m/Y');
                break;
            case 2:
                $this->periode = 'T2 ' . $year;
                $this->prompt_date = Carbon::createFromDate($year, 6, 30)->format('d/m/Y');
                break;
            case 3:
                $this->periode = 'T3 ' . $year;
                $this->prompt_date = Carbon::createFromDate($year, 9, 30)->format('d/m/Y');
                break;
            case 4:
                $this->periode = 'T4 ' . $year;
                $this->prompt_date = Carbon::createFromDate($year, 12, 31)->format('d/m/Y');
                break;
            default:
                $this->periode = 'Non définie';
                $this->prompt_date = null;
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
        $id = $this->periodes_all->sortByDesc('id')->first()->id;
        $this->periode_actuelle = Periode::find($id);

    }
    public function statistique()
    {
        $user = auth()->user();
        $entity_role = $user->role->nom_role;
        $entity_id = $user->entity_id;

        if ($entity_role == 'District') {
            $fs_ids = FormationSanitaire::where('district_id', $entity_id)->pluck('id');
            $this->nb_asc = FormationSanitaire::whereIn('id', $fs_ids)->sum('nb_asc');
            $this->nb_fs = $fs_ids->count();
            $this->nb_asc_soumission = Consommation::where('acteur', 'ASC')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'soumis')
                ->where('periode_id', $this->periode_actuelle->id)
                ->count();
            $this->nb_fs_soumission = Consommation::where('acteur', 'FS')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'soumis')
                ->where('periode_id', $this->periode_actuelle->id)
                ->count();
            $this->nb_asc_valide = Consommation::where('acteur', 'ASC')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'valide')
                ->where('periode_id', $this->periode_actuelle->id)
                ->count();
            $this->nb_fs_valide = Consommation::where('acteur', 'FS')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'valide')
                ->where('periode_id', $this->periode_actuelle->id)
                ->count();
            $this->fs_prompt = Consommation::where('acteur', 'FS')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'soumis')
                ->where('periode_id', $this->periode_actuelle->id)
                ->where('submitted_at', '>=', $this->prompt_date)
                ->count();
            $this->asc_prompt = Consommation::where('acteur', 'ASC')
                ->whereIn('formation_sanitaire_id', $fs_ids)
                ->where('etat', 'soumis')
                ->where('periode_id', $this->periode_actuelle->id)
                ->where('submitted_at', '>=', $this->prompt_date)
                ->count();
        }
    }
    public function chercherStatistiques()
    {
        $this->periode_actuelle = Periode::find($this->periode_search);
        $this->statistique();
        $this->soumissions = Consommation::with('formationSanitaire', 'periode')
            ->whereHas('formationSanitaire', function ($query) {
                $query->where('district_id', $this->district_id)->where('periode_id', $this->periode_actuelle->id);

            })
            ->where('etat', 'soumis')
            ->orderByDesc('submitted_at')
            ->get();
        $this->validations = Consommation::with('formationSanitaire', 'periode')
            ->whereHas('formationSanitaire', function ($query) {
                $query->where('district_id', $this->district_id)->where('periode_id', $this->periode_actuelle->id);

            })
            ->where('etat', 'valide')
            ->orderByDesc('updated_at')
            ->get();

        //Médicaments les plus commandés (somme de cmd_trim_svt)
        $query = DB::table('consommation_medicament as cm')
            ->join('consommations as c', 'c.id', '=', 'cm.consommation_id')
            ->join('formations_sanitaires as fs', 'fs.id', '=', 'c.formation_sanitaire_id')
            ->join('medicaments as m', 'm.id', '=', 'cm.medicament_id')
            ->select('m.nom', DB::raw('SUM(cm.cmd_trim_svt) as total_commande'))
            ->where('fs.district_id', $this->district_id);
        if ($this->periode_search) {
            $query->where('c.periode_id', $this->periode_search);
        }
        $this->topCommandes = $query
            ->groupBy('m.id', 'm.nom')
            ->orderByDesc('total_commande')
            ->limit(3)
            ->get();




    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function chargerConsommations()
    {
        $user = $this->user;

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
