<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consommation extends Model
{
    protected $table = 'consommations';

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
