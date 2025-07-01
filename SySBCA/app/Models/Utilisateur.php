<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = "utilisateurs";

    public function entity(){
        return $this->morphTo();
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
}
