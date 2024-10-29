<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        if (Auth::check()) {
            $user->created_by = Auth::user()->id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        if (Auth::check()) {
            $user->updated_by = Auth::user()->id;
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
