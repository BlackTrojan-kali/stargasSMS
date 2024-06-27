<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MagazinierController extends Controller
{
    //
    public function show(Request $request){
        return view('manager.dashboard');
    }
}
