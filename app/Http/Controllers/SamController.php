<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SamController extends Controller
{
    
    public function education()
    {
        return  view('sam.education');
    }

    public function test()
    {
       return view('sam.test');
    }

    public function test2()
    {
        return view('sam.test2');
    }

    
    public function test3()
    {
        return view('sam.test3');
    }

    public function getStates()
    {
        $states = Storage::get('states.json');
        return $states;
    }

    public function getBibhags()
    {
        $bibhags = Storage::get('bibhags.json');
        return $bibhags;
    }

    public function getZillas()
    {
        $zillas = Storage::get('zillas.json');
        return $zillas;
    }
}
