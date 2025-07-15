<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periodes';

    public function consommations()
    {
        return $this->hasMany(Consommation::class);
    }
}
