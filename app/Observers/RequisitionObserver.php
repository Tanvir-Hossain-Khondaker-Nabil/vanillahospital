<?php

namespace App\Observers;

use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;

class RequisitionObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Requisition  $requisition
     * @return void
     */
    public function creating(Requisition $requisition)
    {
        if (Auth::check()) {
            $requisition->created_by = Auth::user()->id;
            $requisition->member_id = Auth::user()->member_id;
            $requisition->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Requisition  $requisition
     * @return void
     */
    public function updating(Requisition $requisition)
    {
        if (Auth::check()) {
            $requisition->updated_by = Auth::user()->id;
        }
    }
}
