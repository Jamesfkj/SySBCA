<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationSanitaire extends Model
{
    protected $table = "formations_sanitaires";

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function utilisateur(){
        return $this->morphOne(Utilisateur::class, 'entity');
    }
}
