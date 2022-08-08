<?php

namespace App\Observers;

use App\Models\Static_pages;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class StaticPagesObserver
{
    /**
     * Handle the Static_pages "created" event.
     *
     * @param  \App\Models\Static_pages  $static_pages
     * @return void
     */
    public function created(Static_pages $static_pages)
    {
        $getDirty = $static_pages->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($static_pages, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Static_pages "updated" event.
     *
     * @param  \App\Models\Static_pages  $static_pages
     * @return void
     */
    public function updated(Static_pages $static_pages)
    {
        $getDirty = $static_pages->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $static_pages->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($static_pages, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Static_pages "deleted" event.
     *
     * @param  \App\Models\Static_pages  $static_pages
     * @return void
     */
    public function deleted(Static_pages $static_pages)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $static_pages->id;
            try{
                dispatch(new AdminActivityJob($static_pages, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }   
    }

    /**
     * Handle the Static_pages "restored" event.
     *
     * @param  \App\Models\Static_pages  $static_pages
     * @return void
     */
    public function restored(Static_pages $static_pages)
    {
        //
    }

    /**
     * Handle the Static_pages "force deleted" event.
     *
     * @param  \App\Models\Static_pages  $static_pages
     * @return void
     */
    public function forceDeleted(Static_pages $static_pages)
    {
        //
    }
}
