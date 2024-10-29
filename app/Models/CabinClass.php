<?php

namespace App\Models;

use App\Models\Room;
use App\Models\Company;
use App\Models\CabinSubClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CabinClass extends Model
{
    use SoftDeletes;
    public function subClass(){
      return $this->hasMany(CabinSubClass::class);
    }
    // public function room(){
    //   return $this->subClass()->hasMany(CabinSubClass::class);
    // }
    public function room()
    {
        return $this->hasManyThrough(Room::class, CabinSubClass::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
