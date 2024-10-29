<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/9/2019
 * Time: 1:51 PM
 */
namespace App\Observers;

use App\Models\DueCollectionHistory;
use Illuminate\Support\Facades\Auth;

class DueCollectionHistoryObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\DueCollectionHistory  $model
     * @return void
     */
    public function creating(DueCollectionHistory $model)
    {
        if (Auth::check()) {
            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\DueCollectionHistory  $model
     * @return void
     */
    public function updating(DueCollectionHistory $model)
    {
        if (Auth::check()) {
            $model->updated_by = Auth::user()->id;
        }
    }
}
