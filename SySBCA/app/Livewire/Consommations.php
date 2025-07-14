<?php

namespace App\Livewire;

use App\Models\Medicament;
use App\Models\Consommation;
use Livewire\Component;

class Consommations extends Component
{
    public $tableauVisible = true;
    public $formulaireVisible = false;

    public $periode = '';
    public $produit_id;
    public $quantite;
    public $date;

    public $consommations = [];

    protected $rules = [
        'produit_id' => 'required|exists:produits,id',
        'quantite' => 'required|numeric|min:0.1',
        'date' => 'required|date',
    ];

    public function mount()
    {
        $this->chargerConsommations();
    }

    public function render()
    {
        $medicaments = Medicament::all();
        return view('livewire.consommations',
            [
                'medicaments' => $medicaments,
            ]
        );
    }

    public function afficherFormulaire()
    {
        $this->resetValidation();
        $this->reset(['produit_id', 'quantite', 'date']);
        $this->formulaireVisible = true;
        $this->tableauVisible = false;
    }
    public function afficherTableau()
    {
        $this->resetValidation();
        $this->formulaireVisible = false;
        $this->tableauVisible = true;
        $this->chargerConsommations();
    }

   
    public function ajouterConsommation()
    {
        $this->validate();

        Consommation::create([
            'produit_id' => $this->produit_id,
            'quantite' => $this->quantite,
            'date' => $this->date,
        ]);

        session()->flash('message', 'Consommation ajoutée avec succès.');
        $this->afficherTableau();
    }

    public function filtrerParPériode()
    {
        
    }

    private function chargerConsommations()
    {
        $this->consommations = Consommation::latest()->get();
    }
}
