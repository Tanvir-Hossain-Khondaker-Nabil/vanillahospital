<?php

namespace App\Observers;

use App\Models\SaleReturn;
use Illuminate\Support\Facades\Auth;

class SaleReturnObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return void
     */
    public function creating(SaleReturn $saleReturn)
    {
        if (Auth::check()) {
            $saleReturn->created_by = Auth::user()->id;
            $saleReturn->member_id = Auth::user()->member_id;
            $saleReturn->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\SaleReturn  $saleReturn
     * @return void
     */
    public function updating(SaleReturn $saleReturn)
    {
        if (Auth::check()) {
            $saleReturn->updated_by = Auth::user()->id;
        }
    }
}
