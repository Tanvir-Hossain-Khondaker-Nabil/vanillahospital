<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/5/2019
 * Time: 1:25 PM
 */

namespace App\Observers;

use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class AreaObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\Models\Category $area
     * @return void
     */
    public function creating(Area $area)
    {
        if (Auth::check()) {
            $area->created_by = Auth::user()->id;
            $area->member_id = Auth::user()->member_id;
            $area->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \App\Models\Category $area
     * @return void
     */
    public function updating(Area $area)
    {
        if (Auth::check()) {
            $area->updated_by = Auth::user()->id;
        }
    }
}
