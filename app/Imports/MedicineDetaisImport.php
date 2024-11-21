<?php

namespace App\Imports;

use App\Models\Medicine_category;
use App\Models\Medicine_subcategory;
use App\Models\Medicine_details;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Repositories\MedicineDetailsRepository;
use App\Jobs\NewShopMedicineAddQueue;

class MedicineDetaisImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(!empty($row['category_name'])){
                $medical_category = Medicine_category::where('name', $row['category_name'])->first();
                if(empty($medical_category)){
                    $medical_category = Medicine_category::create([
                        'name' => $row['category_name'],
                    ]);
                }
            }
            
            if(!empty($row['medicine_type']) && !empty($medical_category) && !empty($medical_category->id)){
                $userDetailsService = new MedicineDetailsRepository();
                $medicineTypes = $userDetailsService->getMedicineTypeValue();
                $medicine_type_key = array_search($row['medicine_type'], $medicineTypes);
                $medicine_type_get = 0;
                if ($medicine_type_key !== false) {
                    $medicine_type_get = $medicine_type_key;
                }
            }

            if(!empty($row['medicine_name']) && !empty($medical_category) && !empty($medical_category->id) && isset($medicine_type_get)){
                $medicine = Medicine_details::where('medicine_name', $row['medicine_name'])->where('medicine_sku', $row['medicine_sku'])->where('medicine_type', $medicine_type_get)->where('medicine_category_id', $medical_category->id)->first();
                if(!empty($medicine)){                    
                    $medicine->quantity = $row['quantity'];
                    $medicine->mrp_price = $row['mrp_price'];
                    $medicine->save();
                }else{
                    $medicineAdd = Medicine_details::create([
                                        'medicine_category_id' => $medical_category->id,
                                        'medicine_name' => $row['medicine_name'],
                                        'medicine_sku' => $row['medicine_sku'],
                                        'medicine_type' => (isset($medicine_type_get)) ? $medicine_type_get : 0,
                                        'size_dosage' => $row['size_dosage'],
                                        'description' => $row['description'],
                                        'quantity' => $row['quantity'],
                                        'mrp_price' => $row['mrp_price'],
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