<?php

namespace App\Observers;

use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class TransactionDetailsObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return void
     */
    public function creating(TransactionDetail $transactionDetail)
    {
        if (Auth::check()) {
//            $transactionDetail->created_by = Auth::user()->id;
//            $transactionDetail->member_id = Auth::user()->member_id;
            $transactionDetail->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return void
     */
    public function updating(TransactionDetail $transactionDetail)
    {
//        if (Auth::check()) {
//            $transactionDetail->updated_by = Auth::user()->id;
//        }
    }
}
