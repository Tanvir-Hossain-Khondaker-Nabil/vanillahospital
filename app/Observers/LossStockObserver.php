<?php

namespace App\Observers;

use App\Models\LossStockReconcile;
use Illuminate\Support\Facades\Auth;

class LossStockObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Stock  $stock
     * @return void
     */
    public function creating(LossStockReconcile $stock)
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
    public function updating(LossStockReconcile $stock)
    {
        if (Auth::check()) {
            $stock->updated_by = Auth::user()->id;
        }
    }
}
