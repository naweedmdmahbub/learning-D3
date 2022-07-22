<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    use HasFactory;
    public function coordinates_collection(){
        return $this->belongsTo('App\Models\CoordinatesCollection');
    }
}
