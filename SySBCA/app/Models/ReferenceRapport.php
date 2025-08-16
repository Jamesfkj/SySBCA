<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceRapport extends Model
{
    protected $table = 'reference_rapport';

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class , 'user_id');
    }

}
