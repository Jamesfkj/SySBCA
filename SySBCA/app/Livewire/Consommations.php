<?php

namespace App\Livewire;

use App\Models\Medicament;
use App\Models\Consommation;
use App\Models\Periode;
use Livewire\Component;
use Carbon\Carbon;
use function Laravel\Prompts\select;

class Consommations extends Component
{
    public $tableauVisible = true;
    public $formulaireVisible = false;
    public $type_structure;
    public $periode;
    public $periode_choisi;
    public $produit_id;
    public $qte_en_stock;
    public $stock_de_securite;
    public $cmma;
    public $qte_cmd_trim_svt;
    public $periodes_disponibles = [];
    public $quantite;
    public $date;
    public $medicaments;
    public $consommations = [];

    protected $rules = [
        'produit_id' => 'required|exists:produits,id',
        'quantite' => 'required|numeric|min:0.1',
        'date' => 'required|date',
    ];

    public function mount()
    {
        $this->medicaments = Medicament::all();
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

    }
    public function chargerMedicaments()
    {
        if (!$this->type_structure || $this->type_structure === 'FS') {
            $this->medicaments = Medicament::all();
        } elseif ($this->type_structure === 'ASC') {
            $medicaments_fs_only = Medicament::where('fs_only', true)->pluck('id');
            $this->medicaments = Medicament::whereNotIn('id', $medicaments_fs_only)->get();
        }
    }

    public function render()
    {
        return view(
            'livewire.consommations',

        );
    }

    public function afficherFormulaire($id)
    {
        $this->resetValidation();
        $this->reset(['produit_id', 'quantite', 'date']);
        $this->formulaireVisible = true;
        $this->tableauVisible = false;
        $periode_ids_utilisees = Consommation::where('formation_sanitaire_id', $id)
            ->where('acteur', $this->type_structure)
            ->pluck('periode_id')
            ->toArray();

        // On récupère toutes les périodes non utilisées, triées par ordre chronologique
        $periodes = Periode::whereNotIn('id', $periode_ids_utilisees)
            ->orderBy('annee')
            ->orderByRaw("CAST(SUBSTRING(nom, 2, 1) AS UNSIGNED)")
            ->get();

        // On trouve l’index de la période actuelle dans la liste des périodes
        $index = $periodes->search(function ($periode) {
            return $periode->nom === $this->periode;
        });

        // On garde seulement les périodes jusqu’à l'index inclus
        $this->periodes_disponibles = $index !== false
            ? $periodes->slice(max(0, $index - 2), 3)->sortByDesc('id')->values()
            : $periodes->sortByDesc('id')->take(3)->values();


    }

    public function afficherTableau()
    {
        $this->resetValidation();
        $this->formulaireVisible = false;
        $this->tableauVisible = true;
        $this->chargerConsommations();
    }
    public function ajouterConsommation()
    {
        $this->validate();

        Consommation::create([
            'produit_id' => $this->produit_id,
            'quantite' => $this->quantite,
            'date' => $this->date,
        ]);

        session()->flash('message', 'Consommation ajoutée avec succès.');
        $this->afficherTableau();
    }

    public function filtrerParPériode()
    {

    }
    public function calculerStock($index)
    {
        
            $stk = $this->consommations[$index]['stk_dsp_deb_trim'] ?? 0;
            $qte = $this->consommations[$index]['qte_get_in_trim'] ?? 0;
            dd($stk, $qte);
            if ($stk && $qte) {
                $this->consommations[$index]['qte_en_stock'] = $stk + $qte;
            } elseif ($stk && !$qte) {
                $this->consommations[$index]['qte_en_stock'] = $stk;
            }
            elseif (!$stk && $qte) {
                $this->consommations[$index]['qte_en_stock'] = '--';
            } 
        
    }


    public function chargerConsommations()
    {
        $this->consommations = Consommation::all();
    }

}
