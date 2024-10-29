<?php

namespace App\Observers;

use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;

class StockHistoryObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\StockHistory  $stock
     * @return void
     */
    public function creating(StockHistory $stock)
    {
        if (Auth::check()) {
            $stock->member_id = Auth::user()->member_id;
            $stock->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\StockHistory  $stock
     * @return void
     */
    public function updating(StockHistory $stock)
    {
        if (Auth::check()) {
//            $stock->updated_by = Auth::user()->id;
        }
    }
}
