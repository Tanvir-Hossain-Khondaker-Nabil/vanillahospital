<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/23/2019
 * Time: 4:45 PM
 */


namespace App\Observers;

use App\Models\JournalEntryDetail;
use Illuminate\Support\Facades\Auth;

class JournalEntryDetailsObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\JournalEntryDetail  $journalEntryDetails
     * @return void
     */
    public function saving(JournalEntryDetail $journalEntryDetails)
    {
        if (Auth::check()) {
            $journalEntryDetails->created_by = Auth::user()->id;
            $journalEntryDetails->company_id = Auth::user()->company_id;
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\JournalEntryDetail  $journalEntryDetails
     * @return void
     */
    public function updating(JournalEntryDetail $journalEntryDetails)
    {
        if (Auth::check()) {
            $journalEntryDetails->updated_by = Auth::user()->id;
        }
    }
}
