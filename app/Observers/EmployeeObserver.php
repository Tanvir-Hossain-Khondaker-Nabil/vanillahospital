<?php

namespace App\Observers;

use App\Models\EmployeeInfo;
use Illuminate\Support\Facades\Auth;

class EmployeeObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $employee
     * @return void
     */
    public function creating(EmployeeInfo $employee)
    {
        if (Auth::check()) {
            $employee->created_by = Auth::user()->id;
            $employee->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $employee
     * @return void
     */
    public function updating(User $employee)
    {
        if (Auth::check()) {
            $employee->updated_by = Auth::user()->id;
            $employee->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $employee
     * @return void
     */
    public function deleted(User $employee)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $employee
     * @return void
     */
    public function restored(User $employee)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $employee
     * @return void
     */
    public function forceDeleted(User $employee)
    {
        //
    }
}
