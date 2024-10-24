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
use App\Models\User;
use Illuminate\Console\Command;

class NewShopMedicineAddQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    private $medicineId;
    /**
     * Create a new job instance.
     * 
     * @return void
     */
    public function __construct($medicineId)
    {
        $this->medicineId = $medicineId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('NewShopMedicineAddQueue start');
        $userList = User::where('category_id', 7)->get();
        $medicineDetail = Medicine_details::where('id', $this->medicineId)->where('status', 0)->first();
        foreach ($userList as $userKey => $userValue) {
            if(!empty($medicineDetail)){
                $shopMedicineExit = Shop_medicine_details::where('user_id', $userValue->id)->where('medicine_category_id', $medicineDetail->medicine_category_id)->where('medicine_detail_id', $medicineDetail->id)->first();
                if(empty($shopMedicineExit)){
                    Shop_medicine_details::updateOrCreate(
                        [
                            'user_id' => $userValue->id, 
                            'medicine_category_id' => $medicineDetail->medicine_category_id,
                            'medicine_detail_id' => $medicineDetail->id
                        ], 
                        [
                            'capsual_quantity' => !empty($medicineDetail->quantity) ? $medicineDetail->quantity : 0,
                            'mrp_price' => !empty($medicineDetail->mrp_price) ? $medicineDetail->mrp_price : 0,
                            'offer_price' => !empty($medicineDetail->mrp_price) ? $medicineDetail->mrp_price : 0,
                            'status' => 0,
                        ]
                    );
                }             
            }
        }
        Log::info('NewShopMedicineAddQueue end');
    }
}
