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
    public $periodes_all = [];
    public $periode;
    public $fs;
    public $fs_choisie;
    public $formation_sanitaire = [];
    public $confirm_chargement = false;
    public $periode_choisie;
    public $periode_actuelle;
    public $periode_search;
    public $structure_defaut = 'FS';
    public $qte_cmd_trim_svt;
    public $periodes_disponibles = [];
    public $quantites_accordees = [];
    public $date;
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
        $periodes = Periode::where('id', '<=', $id_max)->orderByDesc('id')->get();

        $consommations_existantes = Consommation::where('formation_sanitaire_id', $entity_id)
            ->where('acteur', $this->type_structure)
            ->whereIn('periode_id', $periodes->pluck('id'))
            ->pluck('periode_id')
            ->toArray();

        $this->periodes_disponibles = $periodes->filter(function ($periode) use ($consommations_existantes) {
            return !in_array($periode->id, $consommations_existantes);
        })->values();

        $this->periode_choisie = $this->periodes_disponibles->isNotEmpty()
            ? $this->periodes_disponibles->first()->id
            : null;
    }

    public function chargerMedicaments()
    {
        if (!$this->type_structure) {
            $this->medicaments = collect();
            $this->consommations = [];
            return;
        }

        if ($this->type_structure === 'FS') {
            $this->medicaments = Medicament::all();
        } elseif ($this->type_structure === 'ASC') {
            $medicaments_fs_only = Medicament::where('fs_only', true)->pluck('id');
            $this->medicaments = Medicament::whereNotIn('id', $medicaments_fs_only)->get();
        }
        $this->consommations = [];
        foreach ($this->medicaments as $index => $medicament) {
            $this->consommations[$index] = [
                'medicament_id' => $medicament->id,
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
        if ($this->donneesTemporairesExistantes()) {
            $this->chargerDepuisSession();
        }
        $this->chargerPeriodeActeur();
    }

    public function render()
    {
        return view(
            'livewire.consommations',

        );
    }

    public function afficherFormulaire()
    {
        $this->type_structure = null;
        $this->resetValidation();
        $this->chargerMedicaments();
        $this->formulaireVisible = true;
        $this->tableauVisible = false;

    }

    public function afficherTableau()
    {
        $this->resetValidation();
        $this->formulaireVisible = false;
        $this->tableauVisible = true;
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
                ->exists();
            if ($existe) {
                throw ValidationException::withMessages([
                    'consommation_existe' => 'Une consommation pour cette période et ce type de structure (' . $this->type_structure . ') existe déjà.',
                ]);
            }
            $this->validateInputs();
            $this->calculValeur();
            $consommation = new Consommation();
            $consommation->periode_id = $this->periode_choisie;
            $consommation->acteur = $this->type_structure;
            $consommation->formation_sanitaire_id = $entity['id'];
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
            $this->structure_defaut = $this->type_structure;
            $this->periode_actuelle = Periode::where('id', $this->periode_choisie)->first();
            $this->supprimerDonneesTemporaires();
            $this->chargerConsommations($this->periode_choisie, $this->type_structure);
            $this->afficherTableau();
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
    }

    public function chargerConsommations($periode_id, $acteur)
    {
        $user = auth()->user();

        if ($user->role->nom_role === 'District' && $this->fs) {
            // Charger la consommation de la FS sélectionnée
            $conso = Consommation::where('formation_sanitaire_id', $this->fs)
                ->where('periode_id', $periode_id)
                ->where('acteur', $acteur)
                ->first();

        } else {
            // Cas non-District : charger la conso de l'entité du user
            $conso = Consommation::where('formation_sanitaire_id', $user->entity_id)
                ->where('periode_id', $periode_id)
                ->where('acteur', $acteur)
                ->first();
        }

        if ($conso) {
            $this->consommations_all = ConsommationMedicament::where('consommation_id', $conso->id)->get();
        } else {
            $this->consommations_all = collect();
        }
    }
    public function enregistrerQteAccorde($consommation_id, $medicament_id)
    {
        $this->validate([
            "quantites_accordees.$consommation_id" => 'required|integer|min:0',
        ], [
            "quantites_accordees.$consommation_id.required" => 'Champ requis.',
            "quantites_accordees.$consommation_id.integer" => 'Doit être un entier.',
            "quantites_accordees.$consommation_id.min" => 'Minimum 0.',
        ]);
        $quantite = $this->quantites_accordees[$consommation_id];
        $conso = ConsommationMedicament::where('consommation_id', $consommation_id)
            ->where('medicament_id', $medicament_id)
            ->first();

        if ($conso) {
            $conso->qte_accordee = $quantite;
            $conso->save();
            unset($this->quantites_accordees[$consommation_id]);
            session()->flash('message', 'Quantité enregistrée avec succès.');
        } else {
            session()->flash('message', 'Erreur : données non trouvées.');
        }
        $this->chercherConsommations();
    }
    public function enregistrerTemporairement($index)
    {
        if (empty($this->type_structure) || empty($this->periode_choisie)) {
            session()->flash('error', 'Veuillez sélectionner le type de structure et la période avant de sauvegarder.');
            return;
        }
        if (!isset($this->consommations[$index])) {
            session()->flash('error', 'Données du médicament introuvables.');
            return;
        }
        try {
            $entity_id = auth()->user()->entity_id;
            $cle_session = "consommations_{$this->type_structure}_{$this->periode_choisie}_{$entity_id}";

            $donnees_session = session()->get($cle_session, [
                'metadata' => [
                    'type_structure' => $this->type_structure,
                    'periode_choisie' => $this->periode_choisie,
                    'entity_id' => $entity_id,
                    'created_at' => now()->toDateTimeString(),
                ],
                'consommations' => []
            ]);
            $this->calculValeur();

            $donnees_session['metadata']['updated_at'] = now()->toDateTimeString();

            $donnees_session['consommations'][$index] = [
                'medicament_id' => $this->medicaments[$index]->id ?? null,
                'medicament_nom' => $this->medicaments[$index]->nom ?? null,
                'stk_dsp_deb_trim' => $this->consommations[$index]['stk_dsp_deb_trim'] ?? null,
                'qte_get_in_trim' => $this->consommations[$index]['qte_get_in_trim'] ?? null,
                'qte_en_stock' => $this->consommations[$index]['qte_en_stock'] ?? null,
                'qte_used' => $this->consommations[$index]['qte_used'] ?? null,
                'nb_beneficiaire' => $this->consommations[$index]['nb_beneficiaire'] ?? null,
                'perimee' => $this->consommations[$index]['perimee'] ?? null,
                'perte_avarie' => $this->consommations[$index]['perte_avarie'] ?? null,
                'qte_ret_cameg' => $this->consommations[$index]['qte_ret_cameg'] ?? null,
                'nb_jour_rupture' => $this->consommations[$index]['nb_jour_rupture'] ?? null,
                'qte_stock_fin_trim' => $this->consommations[$index]['qte_stock_fin_trim'] ?? null,
                'stk_de_securite' => $this->consommations[$index]['stk_de_securite'] ?? null,
                'ccma' => $this->consommations[$index]['ccma'] ?? null,
                'cmd_trim_svt' => $this->consommations[$index]['cmd_trim_svt'] ?? null,
                'saved_at' => now()->toDateTimeString()
            ];
            session()->put($cle_session, $donnees_session);

            $nom_medicament = $this->medicaments[$index]->nom ?? "Médicament #" . ($index + 1);
            session()->flash("message_sauvegarde_$index", "Données de '{$nom_medicament}' sauvegardées temporairement");

            \Log::info("Sauvegarde temporaire", [
                'user_id' => auth()->id(),
                'entity_id' => $entity_id,
                'cle_session' => $cle_session,
                'medicament_index' => $index,
                'medicament_nom' => $nom_medicament
            ]);

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
            \Log::error("Erreur sauvegarde temporaire", [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    //checker voir les donnes en sessiion
    public function donneesTemporairesExistantes(): bool
    {
        if (empty($this->type_structure) || empty($this->periode_choisie)) {
            return false;
        }

        $entity_id = auth()->user()->entity_id;
        $cle_session = "consommations_{$this->type_structure}_{$this->periode_choisie}_{$entity_id}";

        $donnees_session = session()->get($cle_session);

        return isset($donnees_session['consommations']);
    }

    public function chargerDepuisSession()
    {
        $this->confirm_chargement = false;

        $entity_id = auth()->user()->entity_id;
        $cle_session = "consommations_{$this->type_structure}_{$this->periode_choisie}_{$entity_id}";

        $donnees_session = session()->get($cle_session);

        if (!$donnees_session || !isset($donnees_session['consommations'])) {
            return;
        }
        foreach ($donnees_session['consommations'] as $index => $donnees) {
            if (isset($this->consommations[$index])) {
                $this->consommations[$index]['stk_dsp_deb_trim'] = $donnees['stk_dsp_deb_trim'] ?? null;
                $this->consommations[$index]['qte_get_in_trim'] = $donnees['qte_get_in_trim'] ?? null;
                $this->consommations[$index]['qte_en_stock'] = $donnees['qte_en_stock'] ?? null;
                $this->consommations[$index]['qte_used'] = $donnees['qte_used'] ?? null;
                $this->consommations[$index]['nb_beneficiaire'] = $donnees['nb_beneficiaire'] ?? null;
                $this->consommations[$index]['perimee'] = $donnees['perimee'] ?? null;
                $this->consommations[$index]['perte_avarie'] = $donnees['perte_avarie'] ?? null;
                $this->consommations[$index]['qte_ret_cameg'] = $donnees['qte_ret_cameg'] ?? null;
                $this->consommations[$index]['nb_jour_rupture'] = $donnees['nb_jour_rupture'] ?? null;
                $this->consommations[$index]['qte_stock_fin_trim'] = $donnees['qte_stock_fin_trim'] ?? null;
                $this->consommations[$index]['stk_de_securite'] = $donnees['stk_de_securite'] ?? null;
                $this->consommations[$index]['ccma'] = $donnees['ccma'] ?? null;
                $this->consommations[$index]['cmd_trim_svt'] = $donnees['cmd_trim_svt'] ?? null;
            }
        }

    }

    public function supprimerDonneesTemporaires()
    {
        if (empty($this->type_structure) || empty($this->periode_choisie)) {
            return;
        }

        $entity_id = auth()->user()->entity_id;
        $cle_session = "consommations_{$this->type_structure}_{$this->periode_choisie}_{$entity_id}";

        session()->forget($cle_session);
        $this->confirm_chargement = false;
        session()->flash('success', 'Données temporaires supprimées.');

    }

}