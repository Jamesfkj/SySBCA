<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSani extends Model
{
    protected $table = "formations_sanitaires";

    public function districts(){
        return $this->belongsTo(District::class);
    }

    public function utilisateur(){
        return $this->morphOne(Utilisateur::class, 'entity');
    }
}
