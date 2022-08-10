<?php

namespace App\Observers;

use App\Models\Permission_category;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class PermissionCategoryObserver
{
    /**
     * Handle the Permission_category "created" event.
     *
     * @param  \App\Models\Permission_category  $permission_category
     * @return void
     */
    public function created(Permission_category $permission_category)
    {
        $getDirty = $permission_category->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($permission_category, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Permission_category "updated" event.
     *
     * @param  \App\Models\Permission_category  $permission_category
     * @return void
     */
    public function updated(Permission_category $permission_category)
    {
        $getDirty = $permission->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $permission_category->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($permission_category, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Permission_category "deleted" event.
     *
     * @param  \App\Models\Permission_category  $permission_category
     * @return void
     */
    public function deleted(Permission_category $permission_category)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $permission_category->id;
            try{
                dispatch(new AdminActivityJob($permission_category, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Permission_category "restored" event.
     *
     * @param  \App\Models\Permission_category  $permission_category
     * @return void
     */
    public function restored(Permission_category $permission_category)
    {
        //
    }

    /**
     * Handle the Permission_category "force deleted" event.
     *
     * @param  \App\Models\Permission_category  $permission_category
     * @return void
     */
    public function forceDeleted(Permission_category $permission_category)
    {
        //
    }
}
