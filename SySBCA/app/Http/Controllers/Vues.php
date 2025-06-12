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
}
