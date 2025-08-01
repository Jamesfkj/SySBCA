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

    public function medicament(){
        return $this->belongsTo(Medicament::class);
    }
    public function formationSanitaire()
{
    return $this->belongsTo(FormationSanitaire::class, 'formation_sanitaire_id');
}

}
