<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/12/2019
 * Time: 1:08 PM
 */


namespace App\Observers;

use App\Models\AccountHeadsBalanceHistory;
use Illuminate\Support\Facades\Auth;

class AccountHeadHistoryObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\Models\AccountHeadsBalanceHistory $model
     * @return void
     */
    public function creating(AccountHeadsBalanceHistory $model)
    {
        if (Auth::check()) {
            $model->member_id = Auth::user()->member_id;
            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "created" event.
     *
     * @param \App\Models\AccountHeadsBalanceHistory $model
     * @return void
     */
    public function saving(AccountHeadsBalanceHistory $model)
    {
        if (Auth::check()) {
            $model->member_id = Auth::user()->member_id;
            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;

        }
    }


    /**
     * Handle the user "updated" event.
     *
     * @param \App\Models\TrackAccountHeadBalance $model
     * @return void
     */
    public function updating(AccountHeadsBalanceHistory $model)
    {
        if (Auth::check()) {
//            $model->updated_by = Auth::user()->id;
        }
    }
}
