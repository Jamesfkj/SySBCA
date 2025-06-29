<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Region; // Assurez-vous que le modèle Region existe et est correctement importé

class Regions extends Component
{
    public $showCreateForm = false;
    public $showEditForm = false;
    public $search = '';

    public $nom = ''; // Propriété pour le nom de la nouvelle région

    public $editId; // Pour l'ID de la région à modifier
    public $editName; // Pour le nom de la région à modifier dans le formulaire d'édition

    // Afficher le formulaire d'ajout
    public function afficherFormulaire()
    {
        $this->reset(['showEditForm', 'editId', 'editName', 'nom']); // Réinitialise les états et champs liés à l'édition
        $this->showCreateForm = true;
    }

    // Afficher le formulaire d'édition
    public function afficherEdition($regionId)
    {
        $region = Region::find($regionId); // Cherche la région dans la base de données
        if ($region) {
            $this->reset(['showCreateForm', 'nom']); // Réinitialise les états et champs liés à la création
            $this->showEditForm = true;
            $this->editId = $region->id;
            $this->editName = $region->nom; // Assurez-vous que 'nom' correspond à votre colonne de base de données
        }
    }

    // Retour à la liste sans formulaire actif
    public function afficherTableau()
    {
        $this->reset(['showCreateForm', 'showEditForm', 'editId', 'editName', 'nom']); // Réinitialise tous les états des formulaires et leurs champs
    }

    // Créer une nouvelle région
    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:regions,nom', // 'nom' est le nom de la colonne dans votre table 'regions'
        ], [
            'nom.required' => 'Le nom de la région est obligatoire.',
            'nom.unique' => 'Cette région existe déjà.',
            'nom.max' => 'Le nom de la région ne peut pas dépasser 255 caractères.',
            'nom.string' => 'Le nom de la région doit être une chaîne de caractères.',
        ]);

        $region = new Region(); // Crée une nouvelle instance du modèle Region
        $region->nom = $this->nom; // Assigne la valeur du champ 'nom' à l'objet région
        $region->save(); // Sauvegarde la nouvelle région

        $this->nom = ''; // Vide le champ après l'ajout

        session()->flash('message', 'Région ajoutée avec succès !');

        // Décidez si vous voulez revenir à la liste ou rester sur le formulaire d'ajout
        $this->afficherTableau(); // Revenir à la liste
    }

    // Mettre à jour une région existante
    public function updateRegion()
    {
        $this->validate([
            // 'nom' est le nom de la colonne dans votre table 'regions'
            'editName' => 'required|string|max:255|unique:regions,nom,' . $this->editId,
        ], [
            'editName.required' => 'Le nom de la région est obligatoire.',
            'editName.unique' => 'Cette région existe déjà.',
            'editName.max' => 'Le nom de la région ne peut pas dépasser 255 caractères.',
            'editName.string' => 'Le nom de la région doit être une chaîne de caractères.',
        ]);

        $region = Region::find($this->editId); // Trouve la région à modifier
        if ($region) {
            $region->nom = $this->editName; // Met à jour la propriété 'nom' de l'objet
            $region->save(); // Sauvegarde les modifications dans la base de données

            session()->flash('message', 'Région mise à jour avec succès !');
            $this->afficherTableau(); // Revenir à la liste après mise à jour
        }
    }

    // Supprimer une région
    public function delete($regionId)
    {
        $region = Region::find($regionId); // Trouve la région à supprimer
        if ($region) {
            $region->delete(); // Supprime la région de la base de données
            session()->flash('message', 'Région supprimée avec succès !');
        }
    }

    // Méthode de rendu du composant
    public function render()
    {
        $query = Region::query(); // Commence une nouvelle requête sur le modèle Region

        if (!empty($this->search)) {
            $query->where('nom', 'like', '%' . $this->search . '%'); // Filtre par nom si la recherche est active
        }

        $regions = $query->orderBy('nom')->get(); // Récupère les régions triées

        return view('livewire.regions', [
            'regions' => $regions,
        ]);
    }
}