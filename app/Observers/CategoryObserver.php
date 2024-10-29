<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function creating(Category $category)
    {
        if (Auth::check()) {
            $category->created_by = Auth::user()->id;
            $category->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updating(Category $category)
    {
        if (Auth::check()) {
            $category->updated_by = Auth::user()->id;
        }
    }
}
