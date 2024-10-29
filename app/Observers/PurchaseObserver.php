<?php

namespace App\Observers;

use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function creating(Purchase $purchase)
    {
        if (Auth::check()) {
            $purchase->created_by = Auth::user()->id;
            $purchase->member_id = Auth::user()->member_id;
            $purchase->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function updating(Purchase $purchase)
    {
        if (Auth::check()) {
            $purchase->updated_by = Auth::user()->id;
        }
    }
}
