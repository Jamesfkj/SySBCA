<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Region;
use Illuminate\Support\Facades\Validator;

class Regions extends Component
{
    public $regions = [];
    public $search = '';

    public $nom = '';         // Pour la création
    public $editName = '';    // Pour l'édition
    public $editId = null;

    public function render()
    {
        $this->regions = Region::query()
            ->when($this->search, fn ($q) => $q->where('nom', 'like', '%' . $this->search . '%'))
            ->orderBy('nom')
            ->get();

        return view('livewire.regions');
    }


    public function edit($id)
    {
        $region = Region::findOrFail($id);
        $this->editId = $region->id;
        $this->editName = $region->nom;
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:regions,nom,' . $this->editId,
        ], [
            'editName.required' => 'Le nom est requis.',
            'editName.unique' => 'Ce nom existe déjà.',
        ]);

        $region = Region::findOrFail($this->editId);
        $region->update(['nom' => $this->editName]);

        $this->reset(['editId', 'editName']);
        session()->flash('message', 'Région mise à jour avec succès.');
    }
}
