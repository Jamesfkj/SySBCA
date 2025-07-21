<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsommationMedicament extends Model
{
    protected $table = 'consommation_medicament';

    public function consommation()
    {
        return $this->belongsTo(Consommation::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

}
