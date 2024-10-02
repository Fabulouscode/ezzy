<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Models\Medicine_category;
use App\Models\Medicine_details;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class MedicineDetaisExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles {

    public function __construct() 
    {
      
    }

    public function getFilename()
    {
        return 'medicine_details';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }
    
    public function query()
    {
        $query = Medicine_details::query()->with(['medicineCategory']);
        
        $query = $query->where('status','0')->orderBy('id','asc');

        return $query;
    }

    public function headings(): array
    {
        return ["Id", "Category Name", "Medicine Name", "Medicine SKU", "Medicine Type", "Size / Dosage", "Description", "Quantity", "MRP Price", "Offer Price"];
    }

    public function map($data): array
    {
        return [
            isset($data->id) ? $data->id : '',
            !empty($data->medicineCategory) && !empty($data->medicineCategory->name) ? $data->medicineCategory->name : '',
            isset($data->medicine_name) ? $data->medicine_name : '',
            isset($data->medicine_sku) ? $data->medicine_sku : '',
            isset($data->medicine_type_name) ? $data->medicine_type_name : '',
            isset($data->size_dosage) ? $data->size_dosage : '',
            isset($data->description) ? $data->description : '',
            isset($data->quantity) ? $data->quantity : '',
            isset($data->mrp_price) ? $data->mrp_price : '',
            isset($data->mrp_price) ? $data->mrp_price : '',
        ];
    }
    
    public function columnFormats(): array
    {
        return [
  
        ];
    }
}