<?php

namespace App\Observers;

use App\Models\AccountType;
use Illuminate\Support\Facades\Auth;

class AccountTypesObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\AccountType  $model
     * @return void
     */
    public function creating(AccountType $model)
    {
        if (Auth::check()) {
//            $model->created_by = Auth::user()->id;
            $model->member_id = Auth::user()->member_id;
            $model->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\AccountType  $model
     * @return void
     */
    public function updating(AccountType $model)
    {
        if (Auth::check()) {
//            $model->updated_by = Auth::user()->id;
        }
    }
}
