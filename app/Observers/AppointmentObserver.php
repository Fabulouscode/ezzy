<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "updated" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        $getDirty = $appointment->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $appointment->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($appointment, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $appointment->id;
            try{
                dispatch(new AdminActivityJob($appointment, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Appointment "restored" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }

    public function cancelled(User $user) 
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $user->id;
            try{
                dispatch(new AdminActivityJob($user, 'cancelled', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }
}
