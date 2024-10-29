<?php

namespace App\Observers;

use App\Models\Membership;
use Illuminate\Support\Facades\Auth;

class MembershipObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function creating(Membership $membership)
    {
        if (Auth::check()) {
            $membership->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function updating(Membership $membership)
    {
        if (Auth::check()) {
            $membership->updated_by = Auth::user()->id;
        }
    }
}
