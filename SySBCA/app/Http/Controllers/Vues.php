<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Vues extends Controller
{
    public function regions()
    {
        return view('user.region');
    }

    public function districts()
    {
        return view('user.district');
    }

    public function  fs()
    {
        return view('user.fs');
    }

    public function utilisateurs()
    {
        return view('user.utilisateurs');
    }
    public function medicament()
    {
        return view('user.medicament');
    }
    public function consommation()
    {
        return view('user.consommation');
    }


}
