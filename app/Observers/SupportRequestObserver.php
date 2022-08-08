<?php

namespace App\Observers;

use App\Models\Support_request;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class SupportRequestObserver
{
    /**
     * Handle the Support_request "created" event.
     *
     * @param  \App\Models\Support_request  $support_request
     * @return void
     */
    public function created(Support_request $support_request)
    {
        //
    }

    /**
     * Handle the Support_request "updated" event.
     *
     * @param  \App\Models\Support_request  $support_request
     * @return void
     */
    public function updated(Support_request $support_request)
    {
        $getDirty = $support_request->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $support_request->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($support_request, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Support_request "deleted" event.
     *
     * @param  \App\Models\Support_request  $support_request
     * @return void
     */
    public function deleted(Support_request $support_request)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $support_request->id;
            try{
                dispatch(new AdminActivityJob($support_request, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Support_request "restored" event.
     *
     * @param  \App\Models\Support_request  $support_request
     * @return void
     */
    public function restored(Support_request $support_request)
    {
        //
    }

    /**
     * Handle the Support_request "force deleted" event.
     *
     * @param  \App\Models\Support_request  $support_request
     * @return void
     */
    public function forceDeleted(Support_request $support_request)
    {
        //
    }
}
