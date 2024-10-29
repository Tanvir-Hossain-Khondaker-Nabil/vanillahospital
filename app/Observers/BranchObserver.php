<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/1/2019
 * Time: 12:46 PM
 */

namespace App\Observers;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class BranchObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Category  $branch
     * @return void
     */
    public function creating(Branch $branch)
    {
        if (Auth::check()) {
            $branch->created_by = Auth::user()->id;
            $branch->member_id = Auth::user()->member_id;
            $branch->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Category  $branch
     * @return void
     */
    public function updating(Branch $branch)
    {
        if (Auth::check()) {
            $branch->updated_by = Auth::user()->id;
        }
    }
}
