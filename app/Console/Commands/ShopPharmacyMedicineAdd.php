<?php

namespace App\Console\Commands;

use App\Models\Medicine_details;
use App\Models\Shop_medicine_details;
use App\Models\User;
use Illuminate\Console\Command;

class ShopPharmacyMedicineAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:medicine-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shop Medicine add';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info('ShopPharmacyMedicineAdd start');
        $userList = User::where('category_id', 7)->where('id', 8459)->get();
        $medicineList = Medicine_details::where('status', 0)->get();
        foreach ($userList as $userKey => $userValue) {
            foreach ($medicineList as $key => $value) {
                $shopMedicineExit = Shop_medicine_details::where('user_id', $userValue->id)->where('medicine_category_id', $value->medicine_category_id)->where('medicine_detail_id', $value->id)->first();
                if(empty($shopMedicineExit)){
                    Shop_medicine_details::updateOrCreate(
                        [
                            'user_id' => $userValue->id, 
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
                }else{
                    if(!empty($shopMedicineExit) && empty($shopMedicineExit->capsual_quantity) && empty($shopMedicineExit->mrp_price)){
                            $shopMedicineExit->capsual_quantity = !empty($value->quantity) ? $value->quantity : 0;
                            $shopMedicineExit->mrp_price = !empty($value->mrp_price) ? $value->mrp_price : 0;
                            $shopMedicineExit->offer_price = !empty($value->mrp_price) ? $value->mrp_price : 0;
                            $shopMedicineExit->status = 0;
                            $shopMedicineExit->save();
                    }else{
                        \Log::info('$shopMedicineExit');
                        \Log::info(json_encode($shopMedicineExit));
                    }

                }
             
            }
        }
        \Log::info('ShopPharmacyMedicineAdd end');
    }
}
