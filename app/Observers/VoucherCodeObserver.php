<?php

namespace App\Observers;

use App\Models\Voucher_code;
use App\Jobs\AdminActivityJob;
use Illuminate\Support\Facades\Auth;

class VoucherCodeObserver
{
    /**
     * Handle the Voucher_code "created" event.
     *
     * @param  \App\Models\Voucher_code  $voucher_code
     * @return void
     */
    public function created(Voucher_code $voucher_code)
    {
        $getDirty = $voucher_code->getDirty();

        $newValues = [];
        $oldValues = null;
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($voucher_code, 'Added', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Voucher_code "updated" event.
     *
     * @param  \App\Models\Voucher_code  $voucher_code
     * @return void
     */
    public function updated(Voucher_code $voucher_code)
    {
        $getDirty = $voucher_code->getDirty();

        $newValues = [];
        $oldValues = [];
        if (count($getDirty) > 0) {
            foreach ($getDirty as $key => $value) {
                $oldValues[$key] = $voucher_code->getOriginal($key);
                $newValues[$key] = $value;
            }
            if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
                $user_id = Auth::guard('admin')->user()->id;
                try{
                    dispatch(new AdminActivityJob($voucher_code, 'Update', null, null, $user_id, $oldValues, $newValues));
                }
                catch (\Throwable $th)
                {
                    
                }
            }
        }
    }

    /**
     * Handle the Voucher_code "deleted" event.
     *
     * @param  \App\Models\Voucher_code  $voucher_code
     * @return void
     */
    public function deleted(Voucher_code $voucher_code)
    {
        if (Auth::guard('admin')->check() && !empty(Auth::guard('admin')->user()->id)) {
            $user_id = Auth::guard('admin')->user()->id;
            $oldValues = $voucher_code->id;
            try{
                dispatch(new AdminActivityJob($voucher_code, 'Deleted', null, null, $user_id, $oldValues));
            }
            catch (\Throwable $th)
            {
                
            }   
        }
    }

    /**
     * Handle the Voucher_code "restored" event.
     *
     * @param  \App\Models\Voucher_code  $voucher_code
     * @return void
     */
    public function restored(Voucher_code $voucher_code)
    {
        //
    }

    /**
     * Handle the Voucher_code "force deleted" event.
     *
     * @param  \App\Models\Voucher_code  $voucher_code
     * @return void
     */
    public function forceDeleted(Voucher_code $voucher_code)
    {
        //
    }
}
