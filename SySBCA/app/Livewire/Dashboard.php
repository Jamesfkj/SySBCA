<?php

namespace App\Livewire;

use App\Models\Consommation;
use App\Models\FormationSanitaire;
use App\Models\Periode;
use Livewire\Component;

class Dashboard extends Component
{
    public $periode;
    public $user;
    public $asc_submit = false;
    public $fs_submit = false;
    public $consommations_fs = [];
    public $consommations_fs_total;
    public $consommations_asc = [];
    public $consommations_asc_total;
    public $periode_actuelle;
    public function mount()
    {
        $this->user = auth()->user();
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
        $this->periode_actuelle = Periode::where('nom', $this->periode)->first();
        $this->chargerConsommations();
        $this->checkSubmit();
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

    public function getConsoInfo()
    {

    }
}
