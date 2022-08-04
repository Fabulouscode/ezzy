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
            
            if(!empty($row['subcategory_name']) && !empty($medical_category) && !empty($medical_category->id)){
                $medicine_subcategory = Medicine_subcategory::where('name', $row['subcategory_name'])->where('medicine_category_id', $medical_category->id)->first();
                if(empty($medicine_subcategory)){                    
                    $medicine_subcategory = Medicine_subcategory::create([
                        'medicine_category_id' => $medical_category->id,
                        'name' => $row['subcategory_name'],
                    ]);
                }
            }
           
            if(!empty($row['medicine_name']) && !empty($medical_category) && !empty($medical_category->id) && !empty($medicine_subcategory) && !empty($medicine_subcategory->id)){
                Medicine_details::create([
                    'medicine_category_id' => $medical_category->id,
                    'medicine_subcategoy_id' => $medicine_subcategory->id,
                    'medicine_name' => $row['medicine_name'],
                    'medicine_sku' => $row['medicine_sku'],
                    'medicine_type' => $row['medicine_type'],
                    'size_dosage' => $row['size_dosage'],
                    'description' => $row['description'],
                ]);    
            }       
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}