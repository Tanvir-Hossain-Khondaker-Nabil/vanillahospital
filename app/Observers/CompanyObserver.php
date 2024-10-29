<?php

namespace App\Observers;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function creating(Company $company)
    {
        if (Auth::check()) {
            $company->created_by = Auth::user()->id;
            $company->member_id = Auth::user()->member_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function updating(Company $company)
    {
        if (Auth::check()) {
            $company->updated_by = Auth::user()->id;
        }
    }

}
