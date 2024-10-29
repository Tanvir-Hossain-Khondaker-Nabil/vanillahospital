<?php

namespace App\Observers;

use App\Models\TrackShoppingBags;
use Illuminate\Support\Facades\Auth;

class TrackShoppingBagsObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\TrackShoppingBags  $model
     * @return void
     */
    public function creating(TrackShoppingBags $model)
    {
        if (Auth::check()) {
            $model->member_id = Auth::user()->member_id;
            $model->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\TrackShoppingBags  $model
     * @return void
     */
    public function updating(TrackShoppingBags $model)
    {
        if (Auth::check()) {
//            $model->updated_by = Auth::user()->id;
        }
    }
}
