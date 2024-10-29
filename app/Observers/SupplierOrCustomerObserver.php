<?php

namespace App\Observers;

use App\Models\SupplierOrCustomer;
use Illuminate\Support\Facades\Auth;

class SupplierOrCustomerObserver
{

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\SupplierOrCustomer  $supplierOrCustomer
     * @return void
     */
    public function creating(SupplierOrCustomer $supplierOrCustomer)
    {
        if (Auth::check()) {
            $supplierOrCustomer->created_by = Auth::user()->id;
            $supplierOrCustomer->member_id = Auth::user()->member_id;
            $supplierOrCustomer->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\SupplierOrCustomer  $supplierOrCustomer
     * @return void
     */
    public function updating(SupplierOrCustomer $supplierOrCustomer)
    {
        if (Auth::check()) {
            $supplierOrCustomer->updated_by = Auth::user()->id;
        }
    }

}
