<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Region; 

class Regions extends Component
{
    public $showCreateForm = false;
    public $showEditForm = false;
    public $search = '';
    public $show = '';
    public $regions = []; 
    public $nom = ''; 
    public $editId;     
    public $editName; 

    public function afficherFormulaire()
    {
        $this->reset(['showEditForm', 'editId', 'editName', 'nom']); 
        $this->showCreateForm = true;
    }

    public function afficherEdition($regionId)
    {
        $region = Region::find($regionId); 
        if ($region) {
            $this->reset(['showCreateForm', 'nom']); 
            $this->showEditForm = true;
            $this->editId = $region->id;
            $this->editName = $region->nom; 
        }
    }

    public function afficherTableau()
    {
        $this->reset(['showCreateForm', 'showEditForm', 'editId', 'editName', 'nom']);
    }

    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:regions,nom', 
        ], [
            'nom.required' => 'Le nom de la région est obligatoire.',
            'nom.unique' => 'Cette région existe déjà.',
            'nom.max' => 'Le nom de la région ne peut pas dépasser 255 caractères.',
            'nom.string' => 'Le nom de la région doit être une chaîne de caractères.',
        ]);

        $region = new Region(); 
        $region->nom = $this->nom; 
        $region->save(); 

        $this->nom = ''; 

        session()->flash('message', 'Région ajoutée avec succès !');

        $this->afficherTableau();
    }

    
    public function updateRegion()
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:regions,nom,' . $this->editId,
        ], [
            'editName.required' => 'Le nom de la région est obligatoire.',
            'editName.unique' => 'Cette région existe déjà.',
            'editName.max' => 'Le nom de la région ne peut pas dépasser 255 caractères.',
            'editName.string' => 'Le nom de la région doit être une chaîne de caractères.',
        ]);

        $region = Region::find($this->editId); 
        if ($region) {
            $region->nom = $this->editName; 
            $region->save(); 

            session()->flash('message', 'Région mise à jour avec succès !');
            $this->afficherTableau(); 
        }
    }

    public function delete($regionId)
    {
        $region = Region::find($regionId); 
        if ($region) {
            $region->delete();
            session()->flash('message', 'Région supprimée avec succès !');
        }
    }

    public function render()
{
    if($this->search) {
       $this->regions = Region::where('nom', 'like', '%' . $this->search . '%')
            ->orderBy('nom', 'asc')
            ->get();
    } else {
        $this->regions = Region::orderBy('nom', 'asc')->get();   
    }
    return view('livewire.regions', [
        'regions' => $this->regions 
    ]);
}
 
}