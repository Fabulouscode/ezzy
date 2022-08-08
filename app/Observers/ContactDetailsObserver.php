<?php

namespace App\Observers;

use App\Models\ContactDetails;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class ContactDetailsObserver
{
    /**
     * Handle the ContactDetails "created" event.
     *
     * @param  \App\Models\ContactDetails  $contactDetails
     * @return void
     */
    public function created(ContactDetails $contactDetails)
    {
        $getDirty = $contactDetails->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($contactDetails, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the ContactDetails "updated" event.
     *
     * @param  \App\Models\ContactDetails  $contactDetails
     * @return void
     */
    public function updated(ContactDetails $contactDetails)
    {
        $getDirty = $contactDetails->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $contactDetails->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($contactDetails, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the ContactDetails "deleted" event.
     *
     * @param  \App\Models\ContactDetails  $contactDetails
     * @return void
     */
    public function deleted(ContactDetails $contactDetails)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $contactDetails->id;
            try{
                dispatch(new AdminActivityJob($contactDetails, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the ContactDetails "restored" event.
     *
     * @param  \App\Models\ContactDetails  $contactDetails
     * @return void
     */
    public function restored(ContactDetails $contactDetails)
    {
        //
    }

    /**
     * Handle the ContactDetails "force deleted" event.
     *
     * @param  \App\Models\ContactDetails  $contactDetails
     * @return void
     */
    public function forceDeleted(ContactDetails $contactDetails)
    {
        //
    }
}
