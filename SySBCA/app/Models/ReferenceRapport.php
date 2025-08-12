<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceRapport extends Model
{
    protected $table = 'reference_rapport';

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

}
