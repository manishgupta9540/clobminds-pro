<?php

namespace App\Observers;

use App\Models\Admin\VoterIdCheck;

class VoterIdCheckObserver
{
    /**
     * Handle the voter id check "created" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function created(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "updated" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function updated(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function deleted(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "restored" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function restored(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "force deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function forceDeleted(VoterIdCheck $voterIdCheck)
    {
        //
    }
}
