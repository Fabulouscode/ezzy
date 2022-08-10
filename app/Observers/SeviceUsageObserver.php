<?php

namespace App\Observers;

use App\Models\Sevice_usage;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class SeviceUsageObserver
{
    /**
     * Handle the Sevice_usage "created" event.
     *
     * @param  \App\Models\Sevice_usage  $sevice_usage
     * @return void
     */
    public function created(Sevice_usage $sevice_usage)
    {
        $getDirty = $sevice_usage->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($sevice_usage, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Sevice_usage "updated" event.
     *
     * @param  \App\Models\Sevice_usage  $sevice_usage
     * @return void
     */
    public function updated(Sevice_usage $sevice_usage)
    {
        $getDirty = $sevice_usage->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $sevice_usage->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($sevice_usage, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Sevice_usage "deleted" event.
     *
     * @param  \App\Models\Sevice_usage  $sevice_usage
     * @return void
     */
    public function deleted(Sevice_usage $sevice_usage)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $sevice_usage->id;
            try{
                dispatch(new AdminActivityJob($sevice_usage, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Sevice_usage "restored" event.
     *
     * @param  \App\Models\Sevice_usage  $sevice_usage
     * @return void
     */
    public function restored(Sevice_usage $sevice_usage)
    {
        //
    }

    /**
     * Handle the Sevice_usage "force deleted" event.
     *
     * @param  \App\Models\Sevice_usage  $sevice_usage
     * @return void
     */
    public function forceDeleted(Sevice_usage $sevice_usage)
    {
        //
    }
}
