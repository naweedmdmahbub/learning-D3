<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatesCollection extends Model
{
    use HasFactory;
    public function geometry(){
        return $this->belongsTo('App\Models\Geometry');
    }
    public function coordinates(){
        return $this->hasMany('App\Models\Coordinate');
    }
}
