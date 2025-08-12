<?php

namespace App\Livewire;

use App\Models\Utilisateur;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profil extends Component
{
    public $utilisateur;
    
    // Mode d'affichage
    public $modeEdition = false;
    public $modeChangementMdp = false;
    
    // Données d'édition du profil
    public $emailEdition;
    public $usernameEdition;
    
    // Données de changement de mot de passe
    public $motDePasseActuel;
    public $nouveauMotDePasse;
    public $confirmerMotDePasse;

    public function mount()
    {
        $this->utilisateur = Auth::user()->load(['role', 'entity']);
        $this->emailEdition = $this->utilisateur->email;
        $this->usernameEdition = $this->utilisateur->username;
    }

    public function activerModeEdition()
    {
        $this->modeEdition = true;
        $this->modeChangementMdp = false;
        $this->resetValidation();
    }

    public function annulerEdition()
    {
        $this->modeEdition = false;
        $this->emailEdition = $this->utilisateur->email;
        $this->usernameEdition = $this->utilisateur->username;
        $this->resetValidation();
    }

    public function activerModeChangementMdp()
    {
        $this->modeChangementMdp = true;
        $this->modeEdition = false;
        $this->resetValidation();
        $this->reset(['motDePasseActuel', 'nouveauMotDePasse', 'confirmerMotDePasse']);
    }

    public function annulerChangementMdp()
    {
        $this->modeChangementMdp = false;
        $this->reset(['motDePasseActuel', 'nouveauMotDePasse', 'confirmerMotDePasse']);
        $this->resetValidation();
    }

    public function mettreAJourProfil()
    {
        $this->validate([
            'usernameEdition' => [
                'required',
                'string',
                'max:255',
            ]
        ], [
            'usernameEdition.required' => 'Le nom d\'utilisateur est obligatoire.',
            'usernameEdition.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'usernameEdition.max' => 'Le nom d\'utilisateur ne peut pas dépasser 255 caractères.'
        ]);

        try {
            $utilisateur = Utilisateur::find(auth()->user()->id);
            $utilisateur->username = $this->usernameEdition;
            $utilisateur->save();
            $this->utilisateur->refresh();
            $this->utilisateur->load(['role', 'entity']);
            $this->modeEdition = false;
            session()->flash('message', 'Profil mis à jour avec succès !');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la mise à jour du profil : ' . $e->getMessage());
        }
    }

    public function changerMotDePasse()
    {
        $this->validate([
            'motDePasseActuel' => 'required',
            'nouveauMotDePasse' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'confirmerMotDePasse' => 'required|same:nouveauMotDePasse',
        ], [
            'motDePasseActuel.required' => 'Le mot de passe actuel est obligatoire.',
            'nouveauMotDePasse.required' => 'Le nouveau mot de passe est obligatoire.',
            'nouveauMotDePasse.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'nouveauMotDePasse.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'confirmerMotDePasse.required' => 'La confirmation du mot de passe est obligatoire.',
            'confirmerMotDePasse.same' => 'La confirmation ne correspond pas au nouveau mot de passe.',
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($this->motDePasseActuel, $this->utilisateur->password)) {
            $this->addError('motDePasseActuel', 'Le mot de passe actuel est incorrect.');
            return;
        }

        try {
            $utilisateur = Utilisateur::find(auth()->user()->id);
            $utilisateur->password = Hash::make($this->nouveauMotDePasse);
            $utilisateur->save();
            $this->utilisateur->refresh();
            $this->utilisateur->load(['role', 'entity']);

            $this->modeChangementMdp = false;
            $this->reset(['motDePasseActuel', 'nouveauMotDePasse', 'confirmerMotDePasse']);
            
            session()->flash('message', 'Mot de passe modifié avec succès !');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du changement de mot de passe : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.profil');
    }
}