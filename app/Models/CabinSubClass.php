<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CabinSubClass extends Model
{
    use SoftDeletes;
    public function room(){
        return $this->belongsToMany(Room::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function cabinClass(){
        return $this->belongsTo(CabinClass::class);
    }
}
