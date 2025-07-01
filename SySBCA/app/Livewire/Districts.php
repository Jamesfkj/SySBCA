<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\District;
use App\Models\Region;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class Districts extends Component
{
    public $afficherFormulaireCreation = false;
    public $afficherFormulaireEdition = false;
    public $recherche = '';
    public $districts = [];
    public $username = '';
    public $mot_de_passe = '';
    public $confirmation_mot_de_passe = '';
    public $nom = '';
    public $region_id = '';
    public $idEdition;
    public $nomEdition;
    public $regionIdEdition;

    public function afficherFormulaire()
    {
        $this->reset(['afficherFormulaireEdition', 'idEdition', 'nomEdition', 'regionIdEdition', 'nom', 'region_id']);
        $this->afficherFormulaireCreation = true;
    }

    public function afficherEdition($districtId)
    {
        $district = District::find($districtId);
        if ($district) {
            $this->reset(['afficherFormulaireCreation', 'nom', 'region_id']);
            $this->afficherFormulaireEdition = true;
            $this->idEdition = $district->id;
            $this->nomEdition = $district->nom;
            $this->regionIdEdition = $district->region_id;
        }
    }

    public function afficherTableau()
    {
        $this->reset(['afficherFormulaireCreation', 'afficherFormulaireEdition', 'idEdition', 'nomEdition', 'regionIdEdition', 'nom', 'region_id']);
    }


    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:districts,nom',
            'region_id' => 'required|exists:regions,id',
            'username' => 'nullable|string|max:255|unique:utilisateurs,username',
            'mot_de_passe' => 'required_with:username|string|min:6|same:confirmation_mot_de_passe',
            'confirmation_mot_de_passe' => 'required_with:username|string|min:6',
        ], [
            'nom.required' => 'Le nom du district est obligatoire.',
            'nom.unique' => 'Ce district existe déjà.',
            'region_id.required' => 'La région est obligatoire.',
            'region_id.exists' => 'La région sélectionnée est invalide.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'mot_de_passe.required_with' => 'Le mot de passe est requis si vous créez un utilisateur.',
            'mot_de_passe.same' => 'Les mots de passe ne correspondent pas.',
            'confirmation_mot_de_passe.required_with' => 'La confirmation est requise.',
        ]);

        $district = new District();
        $district->nom = $this->nom;
        $district->region_id = $this->region_id;
        $district->save(); 
        if (!empty($this->username)) {
            $utilisateur = new Utilisateur();
            $utilisateur->username = $this->username;
            $utilisateur->password = Hash::make($this->mot_de_passe);
            $utilisateur->etat = 'actif';
            $utilisateur->role_id = 3;
            $utilisateur->entity_id = $district->id;
            $utilisateur->entity_type = District::class;
            $utilisateur->doit_renitialiser_pwd = true;
            $utilisateur->save();
        }
        $this->reset(['nom', 'region_id', 'username', 'mot_de_passe', 'confirmation_mot_de_passe']);

        session()->flash('message', 'District ajouté avec succès !');

        $this->afficherFormulaire();
    }


    public function updateDistrict()
    {
        $this->validate([
            'nomEdition' => 'required|string|max:255|unique:districts,nom,' . $this->idEdition,
            'regionIdEdition' => 'required|exists:regions,id',
        ], [
            'nomEdition.required' => 'Le nom du district est obligatoire.',
            'nomEdition.unique' => 'Ce district existe déjà.',
            'nomEdition.max' => 'Le nom du district ne peut pas dépasser 255 caractères.',
            'regionIdEdition.required' => 'La région est obligatoire.',
            'regionIdEdition.exists' => 'La région sélectionnée est invalide.',
        ]);

        $district = District::find($this->idEdition);
        if ($district) {
            $district->nom = $this->nomEdition;
            $district->region_id = $this->regionIdEdition;
            $district->save();

            session()->flash('message', 'District mis à jour avec succès !');
            $this->afficherTableau();
        }
    }

    public function delete($districtId)
    {
        $district = District::find($districtId);
        if ($district) {
            $utilisateur = Utilisateur::where('entity_id', $districtId);
            if ($utilisateur){
                $utilisateur->etat = 'suspendu';
                $utilisateur->save();
            }
            $district->delete();
            session()->flash('message', 'District supprimé avec succès !');
        }
    }

    public function render()
    {
        $query = District::with('region')
            ->when($this->recherche, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->recherche . '%')
                        ->orWhereHas('region', function ($q) {
                            $q->where('nom', 'like', '%' . $this->recherche . '%');
                        });
                });
            })
            ->orderBy('nom', 'asc');

        $this->districts = $query->get();

        return view('livewire.districts', [
            'districts' => $this->districts,
            'regions' => Region::orderBy('nom')->get(),
        ]);
    }
}
