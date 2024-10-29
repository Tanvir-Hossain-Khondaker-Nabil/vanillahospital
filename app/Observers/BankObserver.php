<?php

namespace App\Observers;

use App\Models\Bank;
use Illuminate\Support\Facades\Auth;

class BankObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Bank  $bank
     * @return void
     */
    public function creating(Bank $bank)
    {
        if (Auth::check()) {
            $bank->created_by = Auth::user()->id;
            $bank->member_id = Auth::user()->member_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Bank  $bank
     * @return void
     */
    public function updating(Bank $bank)
    {
        if (Auth::check()) {
            $bank->updated_by = Auth::user()->id;
        }
    }
}
