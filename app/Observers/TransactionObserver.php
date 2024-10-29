<?php

namespace App\Observers;

use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;

class TransactionObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Transactions  $transaction
     * @return void
     */
    public function creating(Transactions $transaction)
    {
        if (Auth::check()) {
            $transaction->created_by = Auth::user()->id;
            $transaction->member_id = Auth::user()->member_id;
            $transaction->company_id = Auth::user()->company_id;
            $transaction->fiscal_year_id = Auth::user()->company->fiscal_year_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updating(Transactions $transaction)
    {
        if (Auth::check()) {
            $transaction->updated_by = Auth::user()->id;
        }
    }

}
