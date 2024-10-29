<?php

namespace App\Observers;

use App\Models\ReturnPurchase;
use Illuminate\Support\Facades\Auth;

class ReturnPurchaseObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\ReturnPurchase  $returnPurchase
     * @return void
     */
    public function creating(ReturnPurchase $returnPurchase)
    {
        if (Auth::check()) {
            $returnPurchase->created_by = Auth::user()->id;
            $returnPurchase->member_id = Auth::user()->member_id;
            $returnPurchase->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\ReturnPurchase  $returnPurchase
     * @return void
     */
    public function updating(ReturnPurchase $returnPurchase)
    {
        if (Auth::check()) {
            $returnPurchase->updated_by = Auth::user()->id;
        }
    }
}
