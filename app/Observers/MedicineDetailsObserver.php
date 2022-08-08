<?php

namespace App\Observers;

use App\Models\Medicine_details;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class MedicineDetailsObserver
{
    /**
     * Handle the Medicine_details "created" event.
     *
     * @param  \App\Models\Medicine_details  $medicine_details
     * @return void
     */
    public function created(Medicine_details $medicine_details)
    {
        $getDirty = $medicine_details->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medicine_details, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medicine_details "updated" event.
     *
     * @param  \App\Models\Medicine_details  $medicine_details
     * @return void
     */
    public function updated(Medicine_details $medicine_details)
    {
        $getDirty = $medicine_details->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $medicine_details->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medicine_details, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medicine_details "deleted" event.
     *
     * @param  \App\Models\Medicine_details  $medicine_details
     * @return void
     */
    public function deleted(Medicine_details $medicine_details)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $medicine_details->id;
            try{
                dispatch(new AdminActivityJob($medicine_details, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Medicine_details "restored" event.
     *
     * @param  \App\Models\Medicine_details  $medicine_details
     * @return void
     */
    public function restored(Medicine_details $medicine_details)
    {
        //
    }

    /**
     * Handle the Medicine_details "force deleted" event.
     *
     * @param  \App\Models\Medicine_details  $medicine_details
     * @return void
     */
    public function forceDeleted(Medicine_details $medicine_details)
    {
        //
    }
}
