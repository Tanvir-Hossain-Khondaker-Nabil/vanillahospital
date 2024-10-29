<?php

namespace App\Observers;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class SaleObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Purchase  $model
     * @return void
     */
    public function creating(Sale $model)
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
     * @param  \App\Models\Purchase  $model
     * @return void
     */
    public function updating(Sale $model)
    {
        if (Auth::check()) {
            $model->updated_by = Auth::user()->id;
        }
    }
}
