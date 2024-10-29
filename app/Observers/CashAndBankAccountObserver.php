<?php

namespace App\Observers;

use App\Models\CashOrBankAccount;
use Illuminate\Support\Facades\Auth;

class CashAndBankAccountObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\CashOrBankAccount  $model
     * @return void
     */
    public function creating(CashOrBankAccount $model)
    {
        if (Auth::check()) {
            $model->created_by = Auth::user()->id;
            $model->member_id = Auth::user()->member_id;
            $model->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\CashOrBankAccount  $model
     * @return void
     */
    public function updating(CashOrBankAccount $model)
    {
        if (Auth::check()) {
            $model->updated_by = Auth::user()->id;
        }
    }

}
