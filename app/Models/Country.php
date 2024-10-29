<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable =[ 'countryCode','countryName','currencyCode','fipsCode','fipsCode','north','south',
        'east','east','west','capital','continentName','languages','continent','isoAlpha3','geonameId',
        'phonecode'
    ];


}
