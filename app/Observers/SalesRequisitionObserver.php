<?php

namespace App\Observers;

use App\Models\SalesRequisition;
use Illuminate\Support\Facades\Auth;

class SalesRequisitionObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\SalesRequisition  $requisition
     * @return void
     */
    public function creating(SalesRequisition $requisition)
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
     * @param  \App\Models\SalesRequisition  $requisition
     * @return void
     */
    public function updating(SalesRequisition $requisition)
    {
        if (Auth::check()) {
            $requisition->updated_by = Auth::user()->id;
        }
    }
}
