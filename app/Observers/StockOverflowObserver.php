<?php

namespace App\Observers;

use App\Models\StockOverflowReconcile;
use Illuminate\Support\Facades\Auth;

class StockOverflowObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function creating(StockOverflowReconcile $stock)
    {
        if (Auth::check()) {
            $stock->created_by = Auth::user()->id;
            $stock->member_id = Auth::user()->member_id;
            $stock->company_id = Auth::user()->company_id;
            $stock->fiscal_year_id = Auth::user()->company->fiscal_year_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function updating(StockOverflowReconcile $stock)
    {
        if (Auth::check()) {
            $stock->updated_by = Auth::user()->id;
        }
    }
}
