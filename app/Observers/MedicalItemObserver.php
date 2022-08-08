<?php

namespace App\Observers;

use App\Models\Medical_item;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class MedicalItemObserver
{
    /**
     * Handle the Medical_item "created" event.
     *
     * @param  \App\Models\Medical_item  $medical_item
     * @return void
     */
    public function created(Medical_item $medical_item)
    {
        $getDirty = $medical_item->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medical_item, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medical_item "updated" event.
     *
     * @param  \App\Models\Medical_item  $medical_item
     * @return void
     */
    public function updated(Medical_item $medical_item)
    {
        $getDirty = $medical_item->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $medical_item->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medical_item, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medical_item "deleted" event.
     *
     * @param  \App\Models\Medical_item  $medical_item
     * @return void
     */
    public function deleted(Medical_item $medical_item)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $medical_item->id;
            try{
                dispatch(new AdminActivityJob($medical_item, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Medical_item "restored" event.
     *
     * @param  \App\Models\Medical_item  $medical_item
     * @return void
     */
    public function restored(Medical_item $medical_item)
    {
        //
    }

    /**
     * Handle the Medical_item "force deleted" event.
     *
     * @param  \App\Models\Medical_item  $medical_item
     * @return void
     */
    public function forceDeleted(Medical_item $medical_item)
    {
        //
    }
}
