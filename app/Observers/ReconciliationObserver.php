<?php

namespace App\Observers;

use App\Models\Reconciliation;
use Illuminate\Support\Facades\Auth;

class ReconciliationObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Reconciliation  $reconciliation
     * @return void
     */
    public function creating(Reconciliation $reconciliation)
    {
        if (Auth::check()) {
            $reconciliation->created_by = Auth::user()->id;
            $reconciliation->member_id = Auth::user()->member_id;
            $reconciliation->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Transaction  $reconciliation
     * @return void
     */
    public function updating(Reconciliation $reconciliation)
    {
        if (Auth::check()) {
            $reconciliation->updated_by = Auth::user()->id;
        }
    }
}
