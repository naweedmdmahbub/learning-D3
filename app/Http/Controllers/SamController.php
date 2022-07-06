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
        $statesFromJson = Storage::get('zillas.json');
        $decoded = json_decode($statesFromJson, true);
        $populations = [];
        $states = [];
        foreach($decoded['features'] as $district){
            $obj['state'] =  $district['properties']['ADM1_EN'];
            $obj['district'] =  $district['properties']['ADM2_EN'];
            $obj['population'] =  rand(1000,5000);
            array_push($populations, $obj);
            if(!in_array($obj['state'], $states)) {
                $states[] = $obj['state'];
            }
        }
        // dd($populations, $states);
        return view('sam.test', compact('states', 'populations'));
    }

    public function test2()
    {
        return view('sam.test2');
    }

    
    public function test3()
    {
        return view('sam.test3');
    }
    
    public function test4()
    {
        return view('sam.test4');
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
