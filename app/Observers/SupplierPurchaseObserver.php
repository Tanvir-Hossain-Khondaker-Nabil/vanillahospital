<?php

namespace App\Observers;

use App\Models\SupplierPurchases;
use Illuminate\Support\Facades\Auth;

class SupplierPurchaseObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\SupplierPurchases  $supplierPurchases
     * @return void
     */
    public function creating(SupplierPurchases $supplierPurchases)
    {
        if (Auth::check()) {
            $supplierPurchases->member_id = Auth::user()->member_id;
            $supplierPurchases->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\SupplierPurchases  $supplierPurchases
     * @return void
     */
    public function updating(SupplierPurchases $supplierPurchases)
    {

    }

}
