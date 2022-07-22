<?php

namespace App\Http\Controllers;

use App\Models\Division;
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
        $divisionsFromJson = Storage::get('zillas.json');
        $decoded = json_decode($divisionsFromJson, true);
        // dd($decoded);
        $populations = [];
        $divisions = [];
        foreach($decoded['features'] as $district){
            $obj['state'] =  $district['properties']['ADM1_EN'];
            $obj['district'] =  $district['properties']['ADM2_EN'];
            $obj['population'] =  rand(1000,10000);
            array_push($populations, $obj);
            if(!in_array($obj['state'], $divisions)) {
                $divisions[] = $obj['state'];
            }
        }
        // dd($populations, $divisions);
        return view('sam.test', compact('divisions', 'populations'));
    }

    public function test2()
    {
        $jsonData = Storage::get('zillas.json');
        // dd($jsonData);
        $divs = Division::with('districts.geometry.coordinates_collections.coordinates')->get();
        // dd($divs);
        $decoded = json_decode($jsonData, true);
        $divisions = [];
        $districts = [];
        $features = [];
        $geometry = [];
        $coordinates_collections = [];
        $coordinates = [];
        $i = 0;
        // dd($divs);
        foreach($divs as $division){
            $divisions[] = $division->name;
            foreach($division->districts as $district){
                $geometry = [];
                // dd($district);
                $coordinates_collections = [];
                foreach($district->geometry->coordinates_collections as $coordinates_collection){
                    $coordinates = [];
                    $single_coordinate_collection = [];
                    // dd($coordinates_collection);
                    foreach($coordinates_collection->coordinates as $coordinate){
                        $single_coordinate = [];
                        array_push($single_coordinate, $coordinate->longitude);
                        array_push($single_coordinate, $coordinate->latitude);
                        array_push($coordinates, $single_coordinate);
                    }
                    // dd($coordinates_collections, $coordinates, $single_coordinate);
                    // $single_coordinate_collection[] = $coordinates;
                    array_push($single_coordinate_collection, $coordinates);
                    array_push($coordinates_collections, $single_coordinate_collection);
                }
                // dd($coordinates_collections, $coordinates);
                // $geometry['type'] = $district->geometry->type;
                $geometry['type'] = ['MultiPolygon'];
                $geometry['coordinates'] = $coordinates_collections;
                // dd($geometry);
                $single_feature['type'] = 'Feature';
                $single_feature['geometry'] = $geometry;
                $single_feature['properties']['division_name'] = $division->name;
                $single_feature['properties']['name'] = $district->name;
                $single_feature['properties']['population'] = $district->population;
                $single_feature['id'] = $i++;
                array_push($features, $single_feature);
            }
        }
        $districts['type'] = "FeatureCollection";
        $districts['features'] = $features;
        // dd($features);
        // dd(json_encode($features));
        return view('sam.test2', compact('divisions', 'districts'));
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
