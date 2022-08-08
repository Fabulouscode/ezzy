<?php

namespace App\Observers;

use App\Models\Services;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class ServicesObserver
{
    /**
     * Handle the Services "created" event.
     *
     * @param  \App\Models\Services  $services
     * @return void
     */
    public function created(Services $services)
    {
        $getDirty = $services->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($services, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Services "updated" event.
     *
     * @param  \App\Models\Services  $services
     * @return void
     */
    public function updated(Services $services)
    {
        $getDirty = $services->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $services->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($services, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Services "deleted" event.
     *
     * @param  \App\Models\Services  $services
     * @return void
     */
    public function deleted(Services $services)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $services->id;
            try{
                dispatch(new AdminActivityJob($services, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Services "restored" event.
     *
     * @param  \App\Models\Services  $services
     * @return void
     */
    public function restored(Services $services)
    {
        //
    }

    /**
     * Handle the Services "force deleted" event.
     *
     * @param  \App\Models\Services  $services
     * @return void
     */
    public function forceDeleted(Services $services)
    {
        //
    }
}
