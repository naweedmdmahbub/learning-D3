<?php

namespace Database\Seeders;

use App\Models\CoordinatesCollection;
use App\Models\District;
use App\Models\Geometry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeometrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = Storage::get('zillas.json');
        $decoded = json_decode($jsonData, true);
        foreach($decoded['features'] as $feature){
            $district =  District::where('name', $feature['properties']['ADM2_EN'])->first();
            DB::table('geometries')->insert([
                'type' => $feature['geometry']['type'],
                'district_id' => $district->id,
            ]);
            $geometry =  Geometry::where('district_id', $district->id)->first();
            if($geometry['type'] == 'Polygon'){
                $coordinates_collection_id = DB::table('coordinates_collections')->insertGetId([
                                                'geometry_id' => $geometry->id,
                                            ]);
                foreach($feature['geometry']['coordinates'] as $coordinates){
                    foreach($coordinates as $coordinate){
                        DB::table('coordinates')->insert([
                            'coordinates_collection_id' => $coordinates_collection_id,
                            'longitude' => $coordinate[0],
                            'latitude' => $coordinate[1],
                        ]);
                    }
                }
            } else {
                foreach($feature['geometry']['coordinates'] as $coordinate_collections){
                    $coordinates_collection_id = DB::table('coordinates_collections')->insertGetId([
                                                    'geometry_id' => $geometry->id,
                                                ]);
                    foreach($coordinate_collections as $coordinates){
                        foreach($coordinates as $coordinate){
                            DB::table('coordinates')->insert([
                                'coordinates_collection_id' => $coordinates_collection_id,
                                'longitude' => $coordinate[0],
                                'latitude' => $coordinate[1],
                            ]);
                        }
                    }
                }

            }
        }
    }
}
