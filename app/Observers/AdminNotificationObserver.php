<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class AdminNotificationObserver
{
    /**
     * Handle the Notification "created" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function created(AdminNotification $notification)
    {
        $getDirty = $notification->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($notification, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Notification "updated" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function updated(AdminNotification $notification)
    {
        $getDirty = $notification->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $notification->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($notification, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Notification "deleted" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function deleted(AdminNotification $notification)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $notification->id;
            try{
                dispatch(new AdminActivityJob($notification, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Notification "restored" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function restored(AdminNotification $notification)
    {
        //
    }

    /**
     * Handle the Notification "force deleted" event.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function forceDeleted(AdminNotification $notification)
    {
        //
    }
}
