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

    public $username = '';
    public $mot_de_passe = '';
    public $confirmation_mot_de_passe = '';
    public $nom = '';
    public $district_id = '';

    public $idEdition;
    public $nomEdition;
    public $districtIdEdition;

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
            'username',
            'mot_de_passe',
            'confirmation_mot_de_passe'
        ]);
    }

    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:formations_sanitaires,nom',
            'district_id' => 'required|exists:districts,id',
            'username' => 'nullable|string|max:255|unique:utilisateurs,username',
            'mot_de_passe' => 'required_with:username|string|min:6|same:confirmation_mot_de_passe',
            'confirmation_mot_de_passe' => 'required_with:username|string|min:6',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Cette formation sanitaire existe déjà.',
            'district_id.required' => 'Le district est obligatoire.',
            'district_id.exists' => 'Le district sélectionné est invalide.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'mot_de_passe.required_with' => 'Le mot de passe est requis si vous créez un utilisateur.',
            'mot_de_passe.same' => 'Les mots de passe ne correspondent pas.',
            'confirmation_mot_de_passe.required_with' => 'La confirmation est requise.',
        ]);

        $fs = new FormationSanitaire();
        $fs->nom = $this->nom;
        $fs->district_id = $this->district_id;
        $fs->save();

        if (!empty($this->username)) {
            $utilisateur = new Utilisateur();
            $utilisateur->username = $this->username;
            $utilisateur->password = Hash::make($this->mot_de_passe);
            $utilisateur->etat = 'actif';
            $utilisateur->role_id = 4;
            $utilisateur->entity_id = $fs->id;
            $utilisateur->entity_type = FormationSanitaire::class;
            $utilisateur->doit_renitialiser_pwd = true;
            $utilisateur->save();
        }

        $this->reset(['nom', 'district_id', 'username', 'mot_de_passe', 'confirmation_mot_de_passe']);

        session()->flash('message', 'Formation sanitaire ajoutée avec succès !');

        $this->afficherFormulaire();
    }

    public function update()
    {
        $this->validate([
            'nomEdition' => 'required|string|max:255|unique:formations_sanitaires,nom,' . $this->idEdition,
            'districtIdEdition' => 'required|exists:districts,id',
        ], [
            'nomEdition.required' => 'Le nom est obligatoire.',
            'nomEdition.unique' => 'Cette formation sanitaire existe déjà.',
            'districtIdEdition.required' => 'Le district est obligatoire.',
            'districtIdEdition.exists' => 'Le district sélectionné est invalide.',
        ]);

        $fs = FormationSanitaire::find($this->idEdition);
        if ($fs) {
            $fs->nom = $this->nomEdition;
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

        $this->formations = $query->get();

        $this->districts = District::orderBy('nom')->get();

        return view('livewire.fs', [
            'formations' => $this->formations,
            'districts' => $this->districts,
        ]);
    }
}
