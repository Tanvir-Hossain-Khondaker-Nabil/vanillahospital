<?php

namespace App\Observers;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function creating(Stock $stock)
    {
        if (Auth::check()) {
            $stock->created_by = Auth::user()->id;
            $stock->member_id = Auth::user()->member_id;
            $stock->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function updating(Stock $stock)
    {
        if (Auth::check()) {
            $stock->updated_by = Auth::user()->id;
        }
    }
}
