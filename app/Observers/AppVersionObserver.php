<?php

namespace App\Observers;

use App\Models\AppVersion;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class AppVersionObserver
{
    /**
     * Handle the AppVersion "created" event.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return void
     */
    public function created(AppVersion $appVersion)
    {
        //
    }

    /**
     * Handle the AppVersion "updated" event.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return void
     */
    public function updated(AppVersion $appVersion)
    {
        $getDirty = $appVersion->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $appVersion->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($appVersion, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the AppVersion "deleted" event.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return void
     */
    public function deleted(AppVersion $appVersion)
    {
        //
    }

    /**
     * Handle the AppVersion "restored" event.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return void
     */
    public function restored(AppVersion $appVersion)
    {
        //
    }

    /**
     * Handle the AppVersion "force deleted" event.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return void
     */
    public function forceDeleted(AppVersion $appVersion)
    {
        //
    }
}
