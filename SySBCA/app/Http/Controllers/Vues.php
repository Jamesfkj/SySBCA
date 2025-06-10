<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Vues extends Controller
{
    public function regions()
    {
        return view('user.region');
    }
}
