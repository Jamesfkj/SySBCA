<?php

namespace App\Livewire;

use Illuminate\Validation\Rules\Exists;
use Livewire\Component;
use App\Models\Region;
use App\Models\District;
use Illuminate\Support\Facades\URL;
use App\Models\FormationSanitaire;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCheckMail;

class Utilisateurs extends Component
{
    public $utilisateurs = [];
    public $roles = [];
    public $regions = [];
    public $districts = [];
    public $formationsSanitaires = [];
    public $recherche = '';
    public $mailerSendService;
    public $username;
    public $email;
    public $mot_de_passe;
    public $confirmation_mot_de_passe;
    public $role_id;
    public $zone_sanitaire;

    public $idEdition;
    public $emailEdition;
    public $usernameEdition;
    public $roleIdEdition;
    public $zone_sanitaireEdition;

    public $afficherFormulaireCreation = false;
    public $afficherFormulaireEdition = false;

    public $zones = [];
    public $role_choisi;

    public function mount()
    {
        $this->roles = Role::all();
        $this->regions = Region::all();
        $this->districts = District::all();
        $this->formationsSanitaires = FormationSanitaire::all();
    }

    public function afficherFormulaire()
    {
        $this->reset(['afficherFormulaireEdition', 'idEdition']);
        $this->afficherFormulaireCreation = true;
    }

    public function afficherEdition($utilisateurId)
    {
        $utilisateur = Utilisateur::findOrFail($utilisateurId);

        $this->reset(['afficherFormulaireCreation']);
        $this->afficherFormulaireEdition = true;

        $this->idEdition = $utilisateur->id;
        $this->usernameEdition = $utilisateur->username;
        $this->emailEdition = $utilisateur->email;
        $this->roleIdEdition = $utilisateur->role_id;
        $this->zone_sanitaireEdition = $utilisateur->entity_id;
    }

    public function afficherTableau()
    {
        $this->reset([
            'afficherFormulaireCreation',
            'afficherFormulaireEdition',
            'username',
            'email',
            'mot_de_passe',
            'confirmation_mot_de_passe',
            'role_id',
            'zone_sanitaire'
        ]);
    }

    public function create()
    {
        $this->validate([
            'email' => 'required|email|unique:utilisateurs,email|max:255',
            'username' => 'required|string|max:255',
            'mot_de_passe' => 'required|string|min:6|same:confirmation_mot_de_passe',
            'role_id' => 'required|exists:roles,id',
            'zone_sanitaire' => [
                Rule::requiredIf(function () {
                    $role = Role::find($this->role_id);
                    return $role && in_array($role->nom_role, ['District', 'Formation sanitaire']);
                }),
                'nullable',
                'integer',
            ]
        ], [
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.email' => "L'adresse e-mail n'est pas valide.",
            'email.unique' => "Cette adresse e-mail est déjà utilisée.",
            'email.max' => "L'adresse e-mail ne doit pas dépasser 255 caractères.",
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'mot_de_passe.same' => 'La confirmation du mot de passe ne correspond pas.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
            'zone_sanitaire.required' => 'La zone sanitaire est obligatoire pour les rôles District ou Formation sanitaire.',
            'zone_sanitaire.integer' => 'La zone sanitaire doit être un nombre entier.',
        ]);

        $utilisateur = new Utilisateur();
        $utilisateur->email = $this->email;
        $utilisateur->username = $this->username;
        $utilisateur->password = Hash::make($this->mot_de_passe);
        $utilisateur->etat = 'inactif';
        $utilisateur->role_id = $this->role_id;
        $role = Role::find($this->role_id);
        $nomRole = Str::lower($role->nom_role);
        $this->assignEntity($utilisateur, $nomRole, $this->zone_sanitaire);
        Mail::to($utilisateur->email)->send(new UserCheckMail($utilisateur));
        $utilisateur->save();
        session()->flash('message', 'Utilisateur créé avec succès !');
        $this->afficherTableau();
    }


    public function envoyerBienvenue($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $this->mailerSendService->envoyerMail(
            $utilisateur->email,
            $utilisateur->username,
            'Bienvenue sur la plateforme !',
            $utilisateur
        );
        return back()->with('message', 'Email de bienvenue envoyé avec succès !');
    }

    public function updateUtilisateur()
    {
        $this->validate([
            'emailEdition' => 'required|email|max:255|unique:utilisateurs,email,' . $this->idEdition,
            'usernameEdition' => 'required|string|max:255',
            'roleIdEdition' => 'required|exists:roles,id',
            'zone_sanitaireEdition' => [
                Rule::requiredIf(function () {
                    $role = Role::find($this->roleIdEdition);
                    return $role && in_array($role->nom_role, ['District', 'Formation sanitaire']);
                }),
                'nullable',
                'integer',
            ]
        ], [
            'emailEdition.required' => "L'adresse e-mail est obligatoire.",
            'emailEdition.email' => "L'adresse e-mail n'est pas valide.",
            'emailEdition.unique' => "Cette adresse e-mail est déjà utilisée par un autre utilisateur.",
            'usernameEdition.required' => "Le nom d'utilisateur est obligatoire.",
            'usernameEdition.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'usernameEdition.max' => "Le nom d'utilisateur ne doit pas dépasser 255 caractères.",
            'roleIdEdition.required' => 'Le rôle est obligatoire.',
            'roleIdEdition.exists' => 'Le rôle sélectionné est invalide.',
            'zone_sanitaireEdition.required' => 'La zone sanitaire est obligatoire pour le rôle sélectionné.',
            'zone_sanitaireEdition.integer' => 'La zone sanitaire doit être un nombre entier.',
        ]);

        $utilisateur = Utilisateur::findOrFail($this->idEdition);
        $utilisateur->email = $this->emailEdition;
        $utilisateur->username = $this->usernameEdition;
        $utilisateur->role_id = $this->roleIdEdition;

        $role = Role::find($this->roleIdEdition);
        $nomRole = Str::lower($role->nom_role);
        $this->assignEntity($utilisateur, $nomRole, $this->zone_sanitaireEdition, $this->idEdition);
        $utilisateur->save();

        session()->flash('message', 'Utilisateur modifié avec succès !');
        $this->afficherTableau();
    }

    private function assignEntity($utilisateur, $nomRole, $zoneId, $excludeId = null)
    {
        $entityMap = [
            'district' => District::class,
            'formation sanitaire' => FormationSanitaire::class,
        ];

        if ($nomRole === 'administrateur') {
            $utilisateur->entity_id = null;
            $utilisateur->entity_type = null;
            $utilisateur->doit_renitialiser_pwd = false;
        } elseif (array_key_exists($nomRole, $entityMap)) {
            $entityClass = $entityMap[$nomRole];

            $query = Utilisateur::where('entity_id', $zoneId)
                ->where('etat', 'actif')
                ->where('entity_type', $entityClass);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if ($query->exists()) {
                $this->addError('zone_sanitaireEdition', "Un utilisateur actif est déjà assigné à cette {$nomRole}.");
                return;
            }

            $utilisateur->entity_id = $zoneId;
            $utilisateur->entity_type = $entityClass;
            $utilisateur->doit_renitialiser_pwd = true;
        } else {
            $utilisateur->entity_id = null;
            $utilisateur->entity_type = null;
            $utilisateur->doit_renitialiser_pwd = false;
        }
    }

    public function suspendre($id)
    {
        $utilisateur = Utilisateur::find($id);
        if ($utilisateur) {
            $utilisateur->etat = 'suspendu';
            $utilisateur->save();
            session()->flash('message', 'Utilisateur suspendu avec succès !');
            $this->afficherTableau();
        }
    }

    public function reactiver($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->etat = 'actif';
        $utilisateur->save();
        session()->flash('message', 'Utilisateur réactivé avec succès.');
    }

    public function delete($id)
    {
        $utilisateur = Utilisateur::find($id);
        if ($utilisateur) {
            $utilisateur->delete();
            session()->flash('message', 'Utilisateur supprimé avec succès !');
            $this->afficherTableau();
        } else {
            session()->flash('error', 'Utilisateur non trouvé.');
        }
    }

    public function render()
    {
        $this->utilisateurs = Utilisateur::with('role')
            ->when($this->recherche, function ($query) {
                $query->where(function ($q) {
                    $q->where('username', 'like', "%{$this->recherche}%")
                        ->orWhere('etat', 'like', "%{$this->recherche}%")
                        ->orWhereDate('created_at', 'like', "%{$this->recherche}%")
                        ->orWhereDate('updated_at', 'like', "%{$this->recherche}%")
                        ->orWhereHas('role', fn($q) => $q->where('nom_role', 'like', "%{$this->recherche}%"))
                        ->orWhereHas('entity', fn($q) => $q->where('nom', 'like', "%{$this->recherche}%"));
                });
            })
            ->orderBy('etat')
            ->orderByDesc('created_at')
            ->get();

        if ($this->role_choisi) {
            $role_choisi = Role::find($this->role_choisi);
            $this->zones = match ($role_choisi?->nom_role) {
                'District' => $this->districts,
                'Formation sanitaire' => $this->formationsSanitaires,
                default => [],
            };
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
