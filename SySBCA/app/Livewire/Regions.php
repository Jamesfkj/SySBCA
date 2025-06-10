<?php

namespace App\Livewire;

use Livewire\Component;

class Regions extends Component
{
    public $moi = 'Ministry of Interior';

    public function render()
    {
        return view('livewire.regions');
    }
}

