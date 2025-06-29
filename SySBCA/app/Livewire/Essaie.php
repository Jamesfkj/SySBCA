<?php

namespace App\Livewire;

use Livewire\Component;

class Essaie extends Component

{
    public int $count = 0;
    public function render()
    {
        return view('livewire.essaie');
    }


    public function increment()
    {
        $this->count++;
    }

}
