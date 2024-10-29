<?php

namespace App\Observers;

use App\Models\DealerSale;
use Illuminate\Support\Facades\Auth;

class DealerSaleObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Purchase  $model
     * @return void
     */
    public function creating(DealerSale $model)
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
    public function updating(DealerSale $model)
    {
        if (Auth::check()) {
            $model->updated_by = Auth::user()->id;
        }
    }
}
