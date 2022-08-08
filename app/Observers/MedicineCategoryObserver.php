<?php

namespace App\Observers;

use App\Models\Medicine_category;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class MedicineCategoryObserver
{
    /**
     * Handle the Medicine_category "created" event.
     *
     * @param  \App\Models\Medicine_category  $medicine_category
     * @return void
     */
    public function created(Medicine_category $medicine_category)
    {
        $getDirty = $medicine_category->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medicine_category, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medicine_category "updated" event.
     *
     * @param  \App\Models\Medicine_category  $medicine_category
     * @return void
     */
    public function updated(Medicine_category $medicine_category)
    {
        $getDirty = $medicine_category->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $medicine_category->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medicine_category, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medicine_category "deleted" event.
     *
     * @param  \App\Models\Medicine_category  $medicine_category
     * @return void
     */
    public function deleted(Medicine_category $medicine_category)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $medicine_category->id;
            try{
                dispatch(new AdminActivityJob($medicine_category, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Medicine_category "restored" event.
     *
     * @param  \App\Models\Medicine_category  $medicine_category
     * @return void
     */
    public function restored(Medicine_category $medicine_category)
    {
        //
    }

    /**
     * Handle the Medicine_category "force deleted" event.
     *
     * @param  \App\Models\Medicine_category  $medicine_category
     * @return void
     */
    public function forceDeleted(Medicine_category $medicine_category)
    {
        //
    }
}
