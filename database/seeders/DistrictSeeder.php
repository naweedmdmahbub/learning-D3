<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisionsFromJson = Storage::get('zillas.json');
        $decoded = json_decode($divisionsFromJson, true);
        foreach($decoded['features'] as $district){
            print_r($district['properties']['ADM1_EN']. ' ');
            $division =  Division::where('name', $district['properties']['ADM1_EN'])->first();
            DB::table('districts')->insert([
                'name' => $district['properties']['ADM2_EN'],
                'division_id' => $division->id,
                'division_pcode' => $district['properties']['ADM1_PCODE'],
                'pcode' => $district['properties']['ADM2_PCODE'],
                'shape_area' => $district['properties']['Shape_Area'],
                'shape_length' => $district['properties']['Shape_Leng'],
            ]);
        }
    }
}
