<?php

namespace App\Observers;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class MemberObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function saving(Member $member)
    {
        if (Auth::check()) {
//            $member->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function updating(Member $member)
    {
        if (Auth::check()) {
            $member->updated_by = Auth::user()->id;
        }
    }
}
