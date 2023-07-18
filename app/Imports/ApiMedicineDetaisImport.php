<?php

namespace App\Imports;

use App\Models\Medicine_category;
use App\Models\Medicine_subcategory;
use App\Models\Medicine_details;
use App\Models\Shop_medicine_details;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ApiMedicineDetaisImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    private $user_id;

    public function __construct(int $user_id) 
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row)
        {
           
            if(!empty($row['category_name']) && !empty($row['medicine_name'])){
                $medicine_detail = Medicine_details::with(['medicineCategory'])
                                    ->whereHas('medicineCategory', function ($query) use ($row) {
                                            $query->orWhere('name', $row['category_name']);
                                    })
                                    ->where('medicine_name', $row['medicine_name'])
                                    ->where('medicine_sku', $row['medicine_sku'])
                                    ->first();
              
                if(!empty($medicine_detail) && !empty($medicine_detail->id) && !empty($row['quantity']) && (!empty($row['mrp_price']) || !empty($row['offer_price']))){
                    if(!empty($row['mrp_price'])){
                        $mrp_price = $row['mrp_price'];
                    }else if(!empty($row['offer_price'])){
                        $mrp_price = $row['offer_price'];
                    }

                    Shop_medicine_details::updateOrCreate(
                    [
                        'user_id' => $this->user_id,
                        'medicine_category_id' => $medicine_detail->medicineCategory->id,
                        'medicine_detail_id' => $medicine_detail->id,
                    ], 
                    [
                        'capsual_quantity' => $row['quantity'],
                        'mrp_price' => $mrp_price,
                        'offer_price' => !empty($row['offer_price']) ? $row['offer_price'] : 0,
                        'status'=>'0'
                    ]);    
                } 
            }
      
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}