<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Medicament;

class Antipaludiques extends Component
{
    public $showCreateForm = false;
    public $showEditForm = false;
    public $search = '';
    public $medicaments = [];

    public $nom = '';
    public $code = '';
    public $composition = '';
    public $fs_only = false;

    public $editId;
    public $editNom;
    public $editCode;
    public $editComposition;
    public $editFsOnly = true;

    public function afficherFormulaire()
    {
        $this->reset([
            'showEditForm',
            'editId',
            'editNom',
            'editCode',
            'editComposition',
            'editFsOnly',
            'nom',
            'code',
            'composition',
            'fs_only'
        ]);
        $this->showCreateForm = true;
    }

    public function afficherEdition($id)
    {
        $medicament = Medicament::find($id);
        if ($medicament) {
            $this->reset([
                'showCreateForm',
                'nom',
                'code',
                'composition',
                'fs_only'
            ]);

            $this->showEditForm = true;
            $this->editId = $medicament->id;
            $this->editNom = $medicament->nom;
            $this->editCode = $medicament->code;
            $this->editComposition = $medicament->composition;
            $this->editFsOnly = $medicament->fs_only;
        }
    }

    public function afficherTableau()
    {
        $this->reset([
            'showCreateForm',
            'showEditForm',
            'editId',
            'editNom',
            'editCode',
            'editComposition',
            'editFsOnly',
            'nom',
            'code',
            'composition',
            'fs_only'
        ]);
    }

    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255|unique:medicaments,nom',
            'code' => 'required|string|max:100',
            'composition' => 'nullable|string|max:500',
            'fs_only' => 'boolean',
        ], [
            'nom.required' => 'Le nom du médicament est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.unique' => 'Ce nom de médicament existe déjà.',

            'code.required' => 'Le code est obligatoire.',
            'code.string' => 'Le code doit être une chaîne de caractères.',
            'code.max' => 'Le code ne peut pas dépasser 100 caractères.',

            'composition.string' => 'La composition doit être une chaîne de caractères.',
            'composition.max' => 'La composition ne peut pas dépasser 500 caractères.',

            'fs_only.boolean' => 'La valeur pour "Uniquement pour Formations Sanitaires" doit être vrai ou faux.',
        ]);


        $medicament = new Medicament();
        $medicament->nom = $this->nom;
        $medicament->code = $this->code;
        $medicament->composition = $this->composition;
        $medicament->fs_only = $this->fs_only;
        $medicament->save();

        session()->flash('message', 'Médicament ajouté avec succès !');
        $this->afficherFormulaire();
    }


    public function updateMedicament()
    {
        $this->validate([
            'editNom' => 'required|string|max:255|unique:medicaments,nom,' . $this->editId,
            'editCode' => 'required|string|max:100',
            'editComposition' => 'nullable|string|max:500',
            'editFsOnly' => 'boolean',
        ], [
            'editNom.required' => 'Le nom du médicament est obligatoire.',
            'editNom.string' => 'Le nom doit être une chaîne de caractères.',
            'editNom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'editNom.unique' => 'Ce nom de médicament existe déjà.',

            'editCode.required' => 'Le code est obligatoire.',
            'editCode.string' => 'Le code doit être une chaîne de caractères.',
            'editCode.max' => 'Le code ne peut pas dépasser 100 caractères.',

            'editComposition.string' => 'La composition doit être une chaîne de caractères.',
            'editComposition.max' => 'La composition ne peut pas dépasser 500 caractères.',

            'editFsOnly.boolean' => 'La valeur pour "Uniquement pour Formations Sanitaires" doit être vrai ou faux.',
        ]);


        $medicament = Medicament::find($this->editId);
        if ($medicament) {
            $medicament->nom = $this->editNom;
            $medicament->code = $this->editCode;
            $medicament->composition = $this->editComposition;
            $medicament->fs_only = $this->editFsOnly; // ✅ corrige ici
            $medicament->save();

            session()->flash('message', 'Médicament mis à jour avec succès !');
            $this->afficherTableau();
        }
    }


    public function delete($id)
    {
        $medicament = Medicament::find($id);
        if ($medicament) {
            $medicament->delete();
            session()->flash('message', 'Médicament supprimé avec succès !');
        }
    }

    public function render()
    {
        $this->medicaments = Medicament::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nom', 'asc')
            ->get();

        return view('livewire.antipaludiques', [
            'medicaments' => $this->medicaments
        ]);
    }
}
