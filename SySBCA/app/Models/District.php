<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "districts";

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function utilisateur(){
        return $this->morphOne(Utilisateur::class, 'entity');
    }
}
