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
    public $qte_en_stock = [];
    public $stock_de_securite;
    public $cmma;
    public $qte_cmd_trim_svt;
    public $periodes_disponibles = [];
    public $quantite;
    public $date;
    public $medicaments;
    public $consommations = [];

    protected $listeners = ['mettreAJourQte'];

    public function mettreAJourQte($index, $valeur)
    {
        $this->consommations[$index]['qte_en_stock'] = $valeur;
    }

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
    public function validateInputs()
    {
        $this->validate([
            'consommations.*.stk_dsp_deb_trim' => 'nullable|integer|min:0',
            'consommations.*.qte_get_in_trim' => 'nullable|integer|min:0',
            'consommations.*.qte_en_stock' => 'nullable|integer|min:0',
            'consommations.*.qte_used' => 'nullable|integer|min:0|lte:consommations.*.qte_en_stock',
            'consommations.*.nb_beneficiaire' => 'nullable|integer|min:0',
            'consommations.*.perimee' => 'nullable|integer|min:0',
            'consommations.*.perte_avarie' => 'nullable|integer|min:0',
            'consommations.*.qte_ret_cameg' => 'nullable|integer|min:0',
            'consommations.*.nb_jour_rupture' => 'nullable|integer|between:0,89',
            'consommations.*.qte_stock_fin_trim' => 'nullable|integer|min:0',
            'consommations.*.stk_de_securite' => 'nullable|integer|min:0',
            'consommations.*.ccma' => 'nullable|integer|min:0',
            'consommations.*.cmd_trim_svt' => 'nullable|integer|min:0',
        ], [
            'consommations.*.stk_dsp_deb_trim.integer' => 'Le stock de début doit être un nombre entier.',
            'consommations.*.stk_dsp_deb_trim.min' => 'Le stock de début ne peut pas être négatif.',

            'consommations.*.qte_get_in_trim.integer' => 'La quantité reçue doit être un nombre entier.',
            'consommations.*.qte_get_in_trim.min' => 'La quantité reçue ne peut pas être négative.',

            'consommations.*.qte_used.integer' => 'La quantité utilisée doit être un nombre entier.',
            'consommations.*.qte_used.min' => 'La quantité utilisée ne peut pas être négative.',
            'consommations.*.qte_used.lte' => 'La quantité utilisée ne peut pas dépasser la quantité en stock.',

            'consommations.*.nb_beneficiaire.integer' => 'Le nombre de bénéficiaires doit être un nombre entier.',
            'consommations.*.nb_beneficiaire.min' => 'Le nombre de bénéficiaires ne peut pas être négatif.',

            'consommations.*.perimee.integer' => 'La quantité périmée doit être un nombre entier.',
            'consommations.*.perimee.min' => 'La quantité périmée ne peut pas être négative.',

            'consommations.*.perte_avarie.integer' => 'La quantité perdue/avariée doit être un nombre entier.',
            'consommations.*.perte_avarie.min' => 'La quantité perdue/avariée ne peut pas être négative.',

            'consommations.*.qte_ret_cameg.integer' => 'La quantité retournée à la CAMEG doit être un nombre entier.',
            'consommations.*.qte_ret_cameg.min' => 'La quantité retournée ne peut pas être négative.',

            'consommations.*.nb_jour_rupture.integer' => 'Le nombre de jours de rupture doit être un nombre entier.',
            'consommations.*.nb_jour_rupture.between' => 'Le nombre de jours de rupture doit être entre 0 et 89.',

            'consommations.*.qte_stock_fin_trim.integer' => 'Le stock de fin de trimestre doit être un nombre entier.',
            'consommations.*.qte_stock_fin_trim.min' => 'Le stock de fin ne peut pas être négatif.',

            'consommations.*.stk_de_securite.integer' => 'Le stock de sécurité doit être un nombre entier.',
            'consommations.*.stk_de_securite.min' => 'Le stock de sécurité ne peut pas être négatif.',

            'consommations.*.ccma.integer' => 'Le CCMA doit être un nombre entier.',
            'consommations.*.ccma.min' => 'Le CCMA ne peut pas être négatif.',

            'consommations.*.cmd_trim_svt.integer' => 'La commande du trimestre suivant doit être un nombre entier.',
            'consommations.*.cmd_trim_svt.min' => 'La commande ne peut pas être négative.',
        ]);
    }

    public function ajouterConsommation()
    {
        dd($this->consommations);
        /*$this->validateInputs();
        if ($this->type_structure && $this->periode_choisi) {
           foreach ($this->consommations as $index => $conso){
            $consommation = new Consommation();
            $consommation->medicament_id = $conso['medicament_id'];

           }
        } else {
            session()->flash('error', 'Veuillez sélectionner une structure et une période valides.');
        }*/
    }
    public function chargerConsommations()
    {
        $this->consommations = Consommation::all();
    }

}