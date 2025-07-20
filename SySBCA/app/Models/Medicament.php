<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    public $table = 'medicaments';

    public function consommation(){
        return $this->belongsToMany(Consommation::class);
    }
}
