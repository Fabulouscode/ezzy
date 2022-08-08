<?php

namespace App\Observers;

use App\Models\Manage_fees;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class ManageFeesObserver
{
    /**
     * Handle the Manage_fees "created" event.
     *
     * @param  \App\Models\Manage_fees  $manage_fees
     * @return void
     */
    public function created(Manage_fees $manage_fees)
    {
        //
    }

    /**
     * Handle the Manage_fees "updated" event.
     *
     * @param  \App\Models\Manage_fees  $manage_fees
     * @return void
     */
    public function updated(Manage_fees $manage_fees)
    {
        
        $getDirty = $manage_fees->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $manage_fees->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($manage_fees, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Manage_fees "deleted" event.
     *
     * @param  \App\Models\Manage_fees  $manage_fees
     * @return void
     */
    public function deleted(Manage_fees $manage_fees)
    {
        //
    }

    /**
     * Handle the Manage_fees "restored" event.
     *
     * @param  \App\Models\Manage_fees  $manage_fees
     * @return void
     */
    public function restored(Manage_fees $manage_fees)
    {
        //
    }

    /**
     * Handle the Manage_fees "force deleted" event.
     *
     * @param  \App\Models\Manage_fees  $manage_fees
     * @return void
     */
    public function forceDeleted(Manage_fees $manage_fees)
    {
        //
    }
}
