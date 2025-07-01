<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Region;
use App\Models\District;
use App\Models\FormationSanitaire;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class Utilisateurs extends Component
{
    public $utilisateurs = [];
    public $roles = [];
    public $regions = [];
    public $districts = [];
    public $formationsSanitaires = [];

    public $recherche = '';

    // Champs création
    public $username;
    public $mot_de_passe;
    public $confirmation_mot_de_passe;
    public $role_id;

    public $role_choisi;
    public $zones = [];
    public $entity_id;
    public $entity_type;



    // Champs édition
    public $idEdition;
    public $usernameEdition;
    public $roleIdEdition;
    public $entityIdEdition;
    public $entityTypeEdition;

    // Contrôle affichage
    public $afficherFormulaireCreation = false;
    public $afficherFormulaireEdition = false;

    public function mount()
    {
        $this->roles = Role::all();
        $this->regions = Region::all();
        $this->districts = District::all();
        $this->formationsSanitaires = FormationSanitaire::all();
    }

    public function afficherFormulaire()
    {
        $this->reset(['afficherFormulaireEdition', 'idEdition', 'usernameEdition', 'roleIdEdition', 'entityIdEdition', 'entityTypeEdition']);
        $this->afficherFormulaireCreation = true;
    }

    public function afficherEdition($utilisateurId)
    {
        $utilisateur = Utilisateur::findOrFail($utilisateurId);
        $this->reset(['afficherFormulaireCreation']);
        $this->afficherFormulaireEdition = true;

        $this->idEdition = $utilisateur->id;
        $this->usernameEdition = $utilisateur->username;
        $this->roleIdEdition = $utilisateur->role_id;
        $this->entityIdEdition = $utilisateur->entity_id;
        $this->entityTypeEdition = $utilisateur->entity_type;
    }

    public function afficherTableau()
    {
        $this->reset(['afficherFormulaireCreation', 'afficherFormulaireEdition', 'username', 'mot_de_passe', 'confirmation_mot_de_passe', 'role_id', 'entity_id', 'entity_type']);
    }

    public function create()
    {
        $this->validate([
            'username' => 'required|string|max:255|unique:utilisateurs,username',
            'mot_de_passe' => 'required|string|min:6|same:confirmation_mot_de_passe',
            'role_id' => 'required',
            'entity_id' => 'required',
            'entity_type' => 'required',
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur ne doit pas dépasser 255 caractères.",
            'username.unique' => "Ce nom d'utilisateur est déjà utilisé.",

            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'mot_de_passe.same' => 'La confirmation du mot de passe ne correspond pas.',

            'role_id.required' => 'Le rôle est obligatoire.',

            'entity_type.required' => 'Le type de rattachement est obligatoire.',
            'entity_type.string' => 'Le type de rattachement doit être une chaîne de caractères.',
        ]);


        $utilisateur = new Utilisateur();
        $utilisateur->username = $this->username;
        $utilisateur->password = Hash::make($this->mot_de_passe);
        $utilisateur->etat = 'actif';
        $utilisateur->role_id = $this->role_id;
        $utilisateur->entity_id = $this->entity_id;
        $utilisateur->entity_type = $this->entity_type;
        $utilisateur->doit_renitialiser_pwd = true;
        $utilisateur->save();

        session()->flash('message', 'Utilisateur créé avec succès !');
        $this->afficherTableau();
    }

    public function updateUtilisateur()
    {
        $this->validate([
            'usernameEdition' => 'required|string|max:255|unique:utilisateurs,username,' . $this->idEdition,
            'roleIdEdition' => 'required|exists:roles,id',
            'entityIdEdition' => 'required|integer',
            'entityTypeEdition' => 'required|string',
        ]);

        $utilisateur = Utilisateur::findOrFail($this->idEdition);
        $utilisateur->username = $this->usernameEdition;
        $utilisateur->role_id = $this->roleIdEdition;
        $utilisateur->entity_id = $this->entityIdEdition;
        $utilisateur->entity_type = $this->entityTypeEdition;
        $utilisateur->save();

        session()->flash('message', 'Utilisateur modifié avec succès !');
        $this->afficherTableau();
    }

    public function delete($id)
    {
        $utilisateur = Utilisateur::find($id);
        if ($utilisateur) {
            $utilisateur->etat = 'suspendu';
            $utilisateur->save();

            session()->flash('message', 'Utilisateur suspendu avec succès !');
        }
    }

    public function render()
    {
        // Requête avec recherche sur l'utilisateur, son rôle et son entité liée
        $this->utilisateurs = Utilisateur::with('role')
            ->when($this->recherche, function ($query) {
                $query->where(function ($q) {
                    $q->where('username', 'like', '%' . $this->recherche . '%')
                        ->orWhere('etat', 'like', '%' . $this->recherche . '%')
                        ->orWhereDate('created_at', 'like', '%' . $this->recherche . '%')
                        ->orWhereDate('updated_at', 'like', '%' . $this->recherche . '%')
                        ->orWhereHas('role', function ($q) {
                            $q->where('nom_role', 'like', '%' . $this->recherche . '%');
                        })
                        ->orWhereHas('entity', function ($q) {
                            $q->where('nom', 'like', '%' . $this->recherche . '%');
                        });
                });
            })
            ->orderBy('username')
            ->get();

        // Rôles
        $this->roles = Role::all();

        // Zones selon le rôle sélectionné
        if ($this->role_choisi) {
            $role_choisi = Role::firstWhere('id', $this->role_choisi);

            if ($role_choisi) {
                switch ($role_choisi->nom_role) {
                    case 'District':
                        $this->zones = $this->districts;
                        break;
                    case 'Formation sanitaire':
                        $this->zones = $this->formationsSanitaires;
                        break;
                }
            }
        }

        return view('livewire.utilisateurs', [
            'utilisateurs' => $this->utilisateurs,
            'roles' => $this->roles,
            'zones' => $this->zones,
            'regions' => $this->regions,
            'districts' => $this->districts,
            'formationsSanitaires' => $this->formationsSanitaires,
        ]);
    }
}
