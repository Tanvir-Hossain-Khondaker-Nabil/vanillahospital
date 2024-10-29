<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerObserver
{

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        if (Auth::check()) {
            $customer->created_by = Auth::user()->id;
            $customer->member_id = Auth::user()->member_id;
            $customer->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updating(Customer $customer)
    {
        if (Auth::check()) {
            $customer->updated_by = Auth::user()->id;
        }
    }

}
