<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function creating(Item $item)
    {
        if (Auth::check()) {
            $item->created_by = Auth::user()->id;
            $item->member_id = Auth::user()->member_id;
            $item->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function updating(Item $item)
    {
        if (Auth::check()) {
            $item->updated_by = Auth::user()->id;
        }
    }
}
