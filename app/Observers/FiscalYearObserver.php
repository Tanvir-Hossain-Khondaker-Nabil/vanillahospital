<?php

namespace App\Observers;

use App\Models\FiscalYear;
use Illuminate\Support\Facades\Auth;

class FiscalYearObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\FiscalYear  $fiscalYear
     * @return void
     */
    public function creating(FiscalYear $fiscalYear)
    {
        if (Auth::check()) {
            $fiscalYear->created_by = Auth::user()->id;
            $fiscalYear->member_id = Auth::user()->member_id;
            $fiscalYear->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\FiscalYear  $fiscalYear
     * @return void
     */
    public function updating(FiscalYear $fiscalYear)
    {
        if (Auth::check()) {
            $fiscalYear->updated_by = Auth::user()->id;
        }
    }

}
