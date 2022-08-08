<?php

namespace App\Observers;

use App\Models\Medical_category;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class MedicalCategoryObserver
{
    /**
     * Handle the Medical_category "created" event.
     *
     * @param  \App\Models\Medical_category  $medical_category
     * @return void
     */
    public function created(Medical_category $medical_category)
    {
        $getDirty = $medical_category->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medical_category, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medical_category "updated" event.
     *
     * @param  \App\Models\Medical_category  $medical_category
     * @return void
     */
    public function updated(Medical_category $medical_category)
    {
        $getDirty = $medical_category->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $medical_category->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($medical_category, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Medical_category "deleted" event.
     *
     * @param  \App\Models\Medical_category  $medical_category
     * @return void
     */
    public function deleted(Medical_category $medical_category)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $medical_category->id;
            try{
                dispatch(new AdminActivityJob($medical_category, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Medical_category "restored" event.
     *
     * @param  \App\Models\Medical_category  $medical_category
     * @return void
     */
    public function restored(Medical_category $medical_category)
    {
        //
    }

    /**
     * Handle the Medical_category "force deleted" event.
     *
     * @param  \App\Models\Medical_category  $medical_category
     * @return void
     */
    public function forceDeleted(Medical_category $medical_category)
    {
        //
    }
}
