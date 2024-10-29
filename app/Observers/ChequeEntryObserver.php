<?php

namespace App\Observers;

use App\Models\ChequeEntry;
use Illuminate\Support\Facades\Auth;

class ChequeEntryObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\ChequeEntry  $cheque_entry
     * @return void
     */
    public function creating(ChequeEntry $cheque_entry)
    {
        if (Auth::check()) {
            $cheque_entry->created_by = Auth::user()->id;
            $cheque_entry->member_id = Auth::user()->member_id;
            $cheque_entry->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\ChequeEntry  $cheque_entry
     * @return void
     */
    public function updating(ChequeEntry $cheque_entry)
    {
        if (Auth::check()) {
            $cheque_entry->updated_by = Auth::user()->id;
        }
    }
}
