<?php

namespace App\Observers;

use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class UnitObserver
{
    /**
    * Handle the user "created" event.
    *
    * @param  \App\Models\Unit  $unit
    * @return void
    */
    public function creating(Unit $unit)
    {
        if (Auth::check()) {
            $unit->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function updating(Unit $unit)
    {
        if (Auth::check()) {
            $unit->updated_by = Auth::user()->id;
        }
    }
}
