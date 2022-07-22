<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geometry extends Model
{
    use HasFactory;
    public function district(){
        return $this->belongsTo('App\Models\District');
    }
    public function coordinates_collections(){
        return $this->hasMany('App\Models\CoordinatesCollection');
    }
}
