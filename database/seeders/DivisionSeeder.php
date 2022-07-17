<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DivisionSeeder extends Seeder
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
            $division =  Division::where('name', $district['properties']['ADM1_EN'])->first();
            if(!$division){
                DB::table('divisions')->insert([
                    'name' => $district['properties']['ADM1_EN'],
                ]);    
            }
        }
    }
}
