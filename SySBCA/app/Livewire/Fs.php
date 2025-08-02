<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\District;
use App\Models\FormationSanitaire;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class Fs extends Component
{
    public $afficherFormulaireCreation = false;
    public $afficherFormulaireEdition = false;
    public $recherche = '';
    public $formations = [];
    public $districts = [];
    public $email;
    public $username = '';
    public $mot_de_passe = '';
    public $confirmation_mot_de_passe = '';
    public $nom = '';
    public $district_id = '';
    public $nb_asc;
    public $idEdition;
    public $nomEdition;
    public $districtIdEdition;
    public $nb_ascEdition;

    public function afficherFormulaire()
    {
        $this->reset(['afficherFormulaireEdition', 'idEdition', 'nomEdition', 'districtIdEdition', 'nom', 'district_id']);
        $this->afficherFormulaireCreation = true;
    }

    public function afficherEdition($id)
    {
        $fs = FormationSanitaire::find($id);
        if ($fs) {
            $this->reset(['afficherFormulaireCreation', 'nom', 'district_id']);
            $this->afficherFormulaireEdition = true;
            $this->nb_ascEdition = $fs->nb_asc;
            $this->idEdition = $fs->id;
            $this->nomEdition = $fs->nom;
            $this->districtIdEdition = $fs->district_id;
        }
    }

    public function afficherTableau()
    {
        $this->reset([
            'afficherFormulaireCreation',
            'afficherFormulaireEdition',
            'idEdition',
            'nomEdition',
            'districtIdEdition',
            'nom',
            'district_id',
        ]);
    }

    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:formations_sanitaires,nom',
            'nb_asc' => 'required|integer|min:0',
            'district_id' => 'required|exists:districts,id',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Cette formation sanitaire existe déjà.',
            'nb_asc.required' => 'Le nombre d\'ASC est obligatoire.',
            'nb_asc.integer' => 'Le nombre d\'ASC doit être un entier.',
            'nb_asc.min' => 'Le nombre d\'ASC ne peut pas être négatif.',
            'district_id.required' => 'Le district est obligatoire.',
            'district_id.exists' => 'Le district sélectionné est invalide.',
        ]);
        $fs = new FormationSanitaire();
        $fs->nom = $this->nom;
        $fs->nb_asc = $this->nb_asc;
        $fs->district_id = $this->district_id;
        $fs->save();$this->reset(['nom', 'district_id', 'nb_asc']);
        session()->flash('message', 'Formation sanitaire ajoutée avec succès !');
        $this->afficherTableau();
    }

    public function update()
    {
        $this->validate([
            'nomEdition' => 'required|string|max:255',
            'nb_ascEdition' => 'required|integer|min:0',
            'districtIdEdition' => 'required|exists:districts,id',
        ], [
            'nomEdition.required' => 'Le nom est obligatoire.',
            'nb_ascEdition.required' => 'Le nombre d\'ASC est obligatoire.',
            'nb_ascEdition.integer' => 'Le nombre d\'ASC doit être un entier.',
            'nb_ascEdition.min' => 'Le nombre d\'ASC ne peut pas être négatif.',
            'districtIdEdition.required' => 'Le district est obligatoire.',
            'districtIdEdition.exists' => 'Le district sélectionné est invalide.',
        ]);

        $fs = FormationSanitaire::find($this->idEdition);
        if ($fs) {
            $fs->nom = $this->nomEdition;
            $fs->nb_asc = $this->nb_ascEdition;
            $fs->district_id = $this->districtIdEdition;
            $fs->save();
            session()->flash('message', 'Formation sanitaire mise à jour avec succès !');
            $this->afficherTableau();
        }
    }
    public function delete($id)
    {
        $fs = FormationSanitaire::find($id);
        if ($fs) {
            $utilisateur = Utilisateur::where('entity_id', $fs->id)
                ->where('entity_type', FormationSanitaire::class)
                ->first();

            if ($utilisateur) {
                $utilisateur->etat = 'suspendu';
                $utilisateur->save();
            }
            $fs->delete();
            session()->flash('message', 'Formation sanitaire supprimée avec succès !');
        }
    }


    public function render()
    {
        $user = auth()->user();
        $query = FormationSanitaire::with('district')
            ->when($this->recherche, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->recherche . '%')
                        ->orWhereHas('district', function ($q) {
                            $q->where('nom', 'like', '%' . $this->recherche . '%');
                        });
                });
            })
            ->orderBy('nom', 'asc');
        if ($user->role->nom_role == 'District') {
            $district_id = $user->entity_id;
            $query = $query->where('district_id', $district_id);
        }
        $this->formations = $query->get();
        $this->districts = District::orderBy('nom')->get();

        return view('livewire.fs', [
            'formations' => $this->formations,
            'districts' => $this->districts,
        ]);
    }
}
