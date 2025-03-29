<?php

namespace App\Livewire;

use App\Models\ConsommationMedicament;
use App\Models\FormationSanitaire;
use App\Models\Medicament;
use App\Models\Consommation;
use App\Models\Periode;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;
use function Laravel\Prompts\select;
use function Pest\Laravel\withMiddleware;

class Consommations extends Component
{
    //les défaut font réferences aux attributs utilisés sur le tableau d'affichage
    public $tableauVisible = true;
    public $formulaireVisible = false;
    public $type_structure;
    public $medicament_choisi;
    public $medicaments_affiches = [];
    //--------------------
    public $showHiddenCards = false;
    //----------------
    public $etat;
    public $periodes_all = [];
    public $periode;
    public $fs;
    public $edit = [];
    public $not_edit = [];
    public $fs_choisie;
    public $formation_sanitaire = [];
    public $conso_passee;
    public $periode_choisie;
    public $periode_actuelle;
    public $periode_search;
    public $structure_defaut = 'FS';
    public $conso;
    public $periodes_disponibles = [];
    public $quantites_accordees = [];
    public $modifierConso = false;
    public $medicaments;
    public $consommations_all = [];
    public $consommations = [];

    protected $listeners = ['mettreAJourQte'];

    public function mettreAJourQte($index, $valeur)
    {
        $this->consommations[$index]['qte_en_stock'] = $valeur;
    }

    public function mount()
    {
        $user = auth()->user();
        if ($user->role->nom_role === 'District') {
            $this->formation_sanitaire = FormationSanitaire::where('district_id', $user->entity_id)->get();
            $this->fs = $this->formation_sanitaire->first()?->id;
            $this->fs_choisie = FormationSanitaire::where('id', $this->fs)->first();
        } elseif ($user->role->nom_role === 'Administrateur') {
            $this->formation_sanitaire = FormationSanitaire::all();
            $this->fs = $this->formation_sanitaire->first()?->id;
            $this->fs_choisie = FormationSanitaire::where('id', $this->fs)->first();
        }
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
        $this->chargerConsommations($this->periode_actuelle->id, $this->structure_defaut);
    }
    public function updatedTypeStructure($value)
    {
        $this->resetValidation();
        if ($value) {
            $this->chargerMedicaments();
        }
    }
    public function chargerPeriodeActeur()
    {
        $entity = auth()->user()->entity;
        $entity_id = $entity['id'];

        $periode_actuelle = Periode::where('nom', $this->periode)->first();

        if (!$periode_actuelle) {
            $this->periodes_disponibles = collect();
            $this->periode_choisie = null;
            return;
        }
        $id_max = $periode_actuelle->id;

        $periodes = Periode::where('id', '<=', $id_max)
            ->orderByDesc('id')
            ->get();

        $consommations_existantes = Consommation::where('formation_sanitaire_id', $entity_id)
            ->where('acteur', $this->type_structure)
            ->whereIn('periode_id', $periodes->pluck('id'))
            ->pluck('periode_id')
            ->toArray();

        $this->periodes_disponibles = $periodes->filter(function ($periode) use ($consommations_existantes) {
            return !in_array($periode->id, $consommations_existantes);
        })->values();

        $this->periode_choisie = $this->periodes_disponibles->isNotEmpty()
            ? $this->periodes_disponibles->sortByDesc('id')->first()->id
            : null;

    }
    public function render()
    {
        return view(
            'livewire.consommations',

        );
    }

    public function afficherFormulaire()
    {
        $this->modifierConso = false;
        $this->type_structure = null;
        $this->periode_choisie = null;
        $this->resetValidation();
        $this->chargerMedicaments();
        $this->formulaireVisible = true;
        $this->tableauVisible = false;

    }
    public function afficherTableau()
    {
        $this->modifierConso = false;
        $this->resetValidation();
        $this->formulaireVisible = false;
        $this->tableauVisible = true;
        $this->periode_choisie = null;
        $this->type_structure = null;
        $this->periode_choisie = null;
        $this->mount();
    }
    public function activerEdition($id)
    {
        $conso = Consommation::find($id);
        if ($conso) {
            $this->type_structure = $conso->acteur;
            $this->periode_choisie = $conso->periode_id;
            $entity_id = auth()->user()->entity_id;
            $conso_check = Consommation::where('formation_sanitaire_id', $entity_id)
                ->where('periode_id', $this->periode_choisie)
                ->where('acteur', $this->type_structure)
                ->first();
            if ($conso_check !== null) {
                $this->periodes_disponibles = Periode::where('id', $this->periode_choisie)->get();
                $this->modifierConso = true;
                $consommations_existantes = ConsommationMedicament::where('consommation_id', $conso_check->id)->get()->keyBy('medicament_id');
                foreach ($this->medicaments as $index => $medicament) {
                    if ($consommations_existantes->has($medicament->id)) {
                        $conso = $consommations_existantes[$medicament->id];
                        $this->consommations[$index] = [
                            'medicament_id' => $medicament->id,
                            'stk_dsp_deb_trim' => $conso->qte_dispo_deb_periode,
                            'qte_get_in_trim' => $conso->qte_recu,
                            'qte_en_stock' => $conso->qte_en_stock,
                            'qte_used' => $conso->qte_utilisee,
                            'nb_beneficiaire' => $conso->nb_beneficiaire,
                            'perimee' => $conso->perimee,
                            'perte_avarie' => $conso->perte_avarie,
                            'qte_ret_cameg' => $conso->qte_retour_cameg,
                            'nb_jour_rupture' => $conso->nb_jour_rupture,
                            'qte_stock_fin_trim' => $conso->qte_restante,
                            'stk_de_securite' => $conso->stock_securite,
                            'ccma' => $conso->cmma,
                            'cmd_trim_svt' => $conso->cmd_trim_svt,
                        ];
                    }
                }
            }
            $this->formulaireVisible = true;
            $this->tableauVisible = false;
        }
    }
    public function chargerMedicaments()
    {
        $entity = auth()->user()->entity_id;
        if (!$this->type_structure) {
            $this->medicaments = collect();
            $this->consommations = [];
            return;
        }
        if ($this->type_structure === 'FS') {
            $this->medicaments = Medicament::all();
        }
        if ($this->type_structure === 'ASC') {
            $medicaments_fs_only = Medicament::where('fs_only', true)->pluck('id');
            $this->medicaments = Medicament::whereNotIn('id', $medicaments_fs_only)->get();
        }
        $this->chargerPeriodeActeur();
        $this->consoPassee();
        $this->reset('consommations');
        foreach ($this->medicaments as $index => $medicament) {
            if (!is_null($this->conso_passee)) {
                $conso_prec = $this->conso_passee->firstWhere('medicament_id', $medicament->id);
                if ($conso_prec) {
                    $stock_debut_attendu = $conso_prec->qte_restante;
                }
            } else {
                $stock_debut_attendu = 0;
            }
            $this->consommations[$index] = [
                'medicament_id' => $medicament->id,
                'stock_debut_attendu' => $stock_debut_attendu,
                'stk_dsp_deb_trim' => null,
                'qte_get_in_trim' => null,
                'qte_en_stock' => null,
                'qte_used' => null,
                'nb_beneficiaire' => null,
                'perimee' => null,
                'perte_avarie' => null,
                'qte_ret_cameg' => null,
                'nb_jour_rupture' => null,
                'qte_stock_fin_trim' => null,
                'stk_de_securite' => null,
                'ccma' => null,
                'cmd_trim_svt' => null,
            ];
        }
    }

    public function consoPassee()
    {
        $entity = auth()->user()->entity_id;
        $periodes = Periode::all();
        $sorted = $periodes->sortByDesc('id')->values();

        $index = $sorted->search(function ($periode) {
            return $periode->id === $this->periode_choisie;
        });
        $periode_précédente_id = $sorted->get($index + 1)?->id;
        $conso_passee = Consommation::where('formation_sanitaire_id', $entity)->where('acteur', $this->type_structure)
            ->where('periode_id', $periode_précédente_id)->first();
        if (!is_null($conso_passee)) {
            $this->conso_passee = ConsommationMedicament::where('consommation_id', $conso_passee->id)->get();
        }
    }

    public function validateInputs()
    {
        $this->validate([
            'consommations.*.stk_dsp_deb_trim' => 'nullable|integer|min:0',
            'consommations.*.qte_get_in_trim' => 'nullable|integer|min:0',
            'consommations.*.qte_en_stock' => 'nullable|integer|min:0',
            'consommations.*.qte_used' => 'nullable|integer|min:0',
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
        $this->nettoyerValeursNul();
        if ($this->periode_choisie && $this->type_structure && !is_null($this->consommations)) {
            $user = auth()->user();
            $entity = $user->entity;

            $existe = Consommation::where('formation_sanitaire_id', $entity['id'])
                ->where('periode_id', $this->periode_choisie)
                ->where('acteur', $this->type_structure)
                ->first();
            $this->validateInputs();
            $this->calculValeur();
            if (!is_null($existe)) {
                foreach ($this->consommations as $conso) {
                    // Cherche le médicament correspondant
                    $consommation_medicament = ConsommationMedicament::where('consommation_id', $existe->id)
                        ->where('medicament_id', $conso['medicament_id'])
                        ->first();
                    if (!$consommation_medicament) {
                        $consommation_medicament = new ConsommationMedicament();
                        $consommation_medicament->consommation_id = $existe->id;
                        $consommation_medicament->medicament_id = $conso['medicament_id'];
                    }
                    $consommation_medicament->qte_dispo_deb_periode = $conso['stk_dsp_deb_trim'];
                    $consommation_medicament->qte_recu = $conso['qte_get_in_trim'];
                    $consommation_medicament->qte_en_stock = $conso['qte_en_stock'];
                    $consommation_medicament->qte_utilisee = $conso['qte_used'];
                    $consommation_medicament->nb_beneficiaire = $conso['nb_beneficiaire'];
                    $consommation_medicament->perimee = $conso['perimee'];
                    $consommation_medicament->perte_avarie = $conso['perte_avarie'];
                    $consommation_medicament->qte_retour_cameg = $conso['qte_ret_cameg'];
                    $consommation_medicament->nb_jour_rupture = $conso['nb_jour_rupture'];
                    $consommation_medicament->qte_restante = $conso['qte_stock_fin_trim'];
                    $consommation_medicament->stock_securite = $conso['stk_de_securite'];
                    $consommation_medicament->cmma = $conso['ccma'];
                    $consommation_medicament->cmd_trim_svt = $conso['cmd_trim_svt'];
                    $consommation_medicament->save();
                }
            } else {
                $consommation = new Consommation();
                $consommation->periode_id = $this->periode_choisie;
                $consommation->acteur = $this->type_structure;
                $consommation->formation_sanitaire_id = $entity['id'];
                $consommation->etat = 'en_cours';
                $consommation->save();

                foreach ($this->consommations as $conso) {
                    $consommation_medicament = new ConsommationMedicament();
                    $consommation_medicament->consommation_id = $consommation->id;
                    $consommation_medicament->medicament_id = $conso['medicament_id'];
                    $consommation_medicament->qte_dispo_deb_periode = $conso['stk_dsp_deb_trim'];
                    $consommation_medicament->qte_recu = $conso['qte_get_in_trim'];
                    $consommation_medicament->qte_en_stock = $conso['qte_en_stock'];
                    $consommation_medicament->qte_utilisee = $conso['qte_used'];
                    $consommation_medicament->nb_beneficiaire = $conso['nb_beneficiaire'];
                    $consommation_medicament->perimee = $conso['perimee'];
                    $consommation_medicament->perte_avarie = $conso['perte_avarie'];
                    $consommation_medicament->qte_retour_cameg = $conso['qte_ret_cameg'];
                    $consommation_medicament->nb_jour_rupture = $conso['nb_jour_rupture'];
                    $consommation_medicament->qte_restante = $conso['qte_stock_fin_trim'];
                    $consommation_medicament->stock_securite = $conso['stk_de_securite'];
                    $consommation_medicament->cmma = $conso['ccma'];
                    $consommation_medicament->cmd_trim_svt = $conso['cmd_trim_svt'];
                    $consommation_medicament->save();
                }
            }

            $this->structure_defaut = $this->type_structure;
            $this->periode_actuelle = Periode::where('id', $this->periode_choisie)->first();
            $this->formulaireVisible = false;
            $this->tableauVisible = true;
            $this->chargerConsommations($this->periode_choisie, $this->type_structure);
        }
    }

    private function nettoyerValeursNul()
    {
        foreach ($this->consommations as $index => $conso) {
            foreach ($conso as $key => $value) {
                if ($value === '' || is_null($value)) {
                    $this->consommations[$index][$key] = 0;
                }
            }
        }
    }

    public function chercherConsommations()
    {
        $this->chargerConsommations($this->periode_search, $this->structure_defaut);
        $this->periode_actuelle = Periode::where('id', $this->periode_search)->first();
        $this->fs_choisie = FormationSanitaire::where('id', $this->fs)->first();
    }
    public function calculValeur()
    {
        foreach ($this->consommations as $index => $conso) {
            $check = array_key_exists('stk_dsp_deb_trim', $conso) && $conso['stk_dsp_deb_trim'] !== null;
            $stk_dispo = $check ? intval($conso['stk_dsp_deb_trim']) : 0;
            $qte_get = isset($conso['qte_get_in_trim']) ? intval($conso['qte_get_in_trim']) : 0;
            $nb_jour_rupture = isset($conso['nb_jour_rupture']) ? intval($conso['nb_jour_rupture']) : 0;
            $qte_used = isset($conso['qte_used']) ? intval($conso['qte_used']) : 0;
            $qte_stock_fin_trim = isset($conso['qte_stock_fin_trim']) ? intval($conso['qte_stock_fin_trim']) : 0;

            if ($check == true) {
                $this->consommations[$index]['qte_en_stock'] = $stk_dispo + $qte_get;
            } else {
                $this->consommations[$index]['qte_en_stock'] = 0;
            }

            if ($stk_dispo > 0 && $qte_used > 0) {
                if ($nb_jour_rupture > 0 && $nb_jour_rupture < 90) {
                    $denom = 90 - $nb_jour_rupture;
                    $cmma = ceil(($qte_used / $denom) * 30);
                    $stk_securite = ceil(($qte_used * 90) / $denom);
                    $cmd_trim_svt = ceil(($cmma * 6) - $qte_stock_fin_trim);
                } else {
                    $cmma = ceil(($qte_used / 90) * 30);
                    $stk_securite = $qte_used;
                    $cmd_trim_svt = ($qte_used * 2) - $qte_stock_fin_trim;
                }

                $this->consommations[$index]['stk_de_securite'] = $stk_securite;
                $this->consommations[$index]['ccma'] = $cmma;
                $this->consommations[$index]['cmd_trim_svt'] = $cmd_trim_svt;
            } else {
                $this->consommations[$index]['stk_de_securite'] = 0;
                $this->consommations[$index]['ccma'] = 0;
                $this->consommations[$index]['cmd_trim_svt'] = 0;
            }
        }
        $this->showHiddenCards = false;
    }

    public function toggleHiddenCards()
    {
        $this->showHiddenCards = !$this->showHiddenCards;
    }

    public function getHiddenCardsCountProperty()
    {
        return collect($this->consommations_all)->filter(function ($consommation) {
            return $consommation->cmma == 0 &&
                $consommation->stock_securite == 0 &&
                $consommation->cmd_trim_svt == 0;
        })->count();
    }
    public function chargerConsommations($periode_id, $acteur)
    {
        $user = auth()->user();

        if (in_array($user->role->nom_role, ['District', 'Administrateur']) && $this->fs) {
            $this->conso = Consommation::where('formation_sanitaire_id', $this->fs)
                ->where('periode_id', $periode_id)
                ->where('acteur', $acteur)
                ->first();
        } else {
            $this->conso = Consommation::where('formation_sanitaire_id', $user->entity_id)
                ->where('periode_id', $periode_id)
                ->where('acteur', $acteur)
                ->first();
        }

        if ($this->conso) {
            $this->consommations_all = ConsommationMedicament::where('consommation_id', $this->conso->id)->get();
            $this->etat = $this->conso->etat;
        } else {
            $this->consommations_all = collect();
            $this->etat = null;
        }

        // 🔁 RÉINITIALISATION ici
        $this->edit = [];
        $this->not_edit = [];

        foreach ($this->consommations_all as $consommation) {
            $id = $consommation->medicament_id;
            $this->edit[$id] = false;
            $this->not_edit[$id] = true;
        }
    }

    public function soumettreConsommation($id)
    {
        $conso = Consommation::where('id', $id)->first();
        if ($conso) {
            $conso->etat = 'soumis';
            $conso->submitted_at = now();
            $conso->save();
            $this->chargerConsommations($conso->periode_id, $conso->acteur);
            $this->conso = $conso;
            session()->flash('message', 'La consommation a été soumise. Elle sera validé par le district');
        }
    }
    public function showEditInput($medicament_id, $consommation_id)
    {
        $this->not_edit[$medicament_id] = false;
        $this->edit[$medicament_id] = true;
        $conso = ConsommationMedicament::where('consommation_id', $consommation_id)
            ->where('medicament_id', $medicament_id)
            ->first();
        $this->quantites_accordees[$medicament_id] = $conso?->qte_accordee ?? null;

    }
    public function enregistrerQteAccorde($consommation_id, $medicament_id)
    {
        $this->validate([
            "quantites_accordees.$medicament_id" => 'required|integer|min:0',
        ], [
            "quantites_accordees.$medicament_id.required" => 'Champ requis.',
            "quantites_accordees.$medicament_id.integer" => 'Doit être un entier.',
            "quantites_accordees.$medicament_id.min" => 'Minimum 0.',
        ]);
        $quantite = $this->quantites_accordees[$medicament_id];
        $conso = ConsommationMedicament::where('consommation_id', $consommation_id)
            ->where('medicament_id', $medicament_id)
            ->first();

        if ($conso) {
            $conso->qte_accordee = $quantite;
            $conso->save();
            unset($this->quantites_accordees[$medicament_id]);
            session()->flash('message', 'Quantité enregistrée avec succès.');
        } else {
            session()->flash('message', 'Erreur : données non trouvées.');
        }
        $this->chercherConsommations();
    }

}