<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Medicine_details;
use App\Models\Shop_medicine_details;

class PharmacyMedicineAdd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    private $user_id;
    /**
     * Create a new job instance.
     * 
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('PharmacyMedicineAdd start');
        $medicineList = Medicine_details::where('status', 0)->get();
        foreach ($medicineList as $key => $value) {

            Shop_medicine_details::updateOrCreate(
                [
                    'user_id' => $this->user_id, 
                    'medicine_category_id' => $value->medicine_category_id,
                    'medicine_detail_id' => $value->id
                ], 
                [
                    'capsual_quantity' => !empty($value->quantity) ? $value->quantity : 0,
                    'mrp_price' => !empty($value->mrp_price) ? $value->mrp_price : 0,
                    'offer_price' => !empty($value->mrp_price) ? $value->mrp_price : 0,
                    'status' => 0,
                ]
            );
        }
        \Log::info('PharmacyMedicineAdd end');
    }
}
