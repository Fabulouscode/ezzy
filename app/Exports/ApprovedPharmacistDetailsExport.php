<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class ApprovedPharmacistDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles {
    private $category_id;
    private $subcategory_id;
    private $status;
    private $start_date;
    private $end_date;
    private $pharmacy_status;
    private $city;
    public function __construct($category_id, $subcategory_id,array $status = [], $start_date = '', $end_date = '', $pharmacy_status = '', $city = '') 
    {
        $this->category_id = $category_id;
        $this->subcategory_id = $subcategory_id;
        $this->status = $status;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->pharmacy_status = $pharmacy_status;
        $this->city = $city;
    }

    public function getFilename()
    {
        return 'approved_pharma_details';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

        ];
    }
    
    public function query()
    {
        
        $query = User::query()->select('users.*')->with(['categoryChild','categoryParent']);    
        if(!empty($this->category_id)){
            $category = $this->category_id;
            $query = $query->whereHas('categoryParent', function ($query) use ($category) {
                $query->where('parent_id', $category);
            });
            
            if(!empty($this->subcategory_id)){
                $query = $query->where('users.category_id', $this->subcategory_id);
            }
            
            if(is_array($this->status)){
                $query = $query->whereIn('users.status', $this->status);
            }else{
                $query = $query->where('users.status', $this->status);
            }
        }else{
            $query = $query->whereNull('users.category_id');
            $query = $query->whereNull('users.subcategory_id');
        }

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime($this->start_date));
            $end_date = date('Y-m-d', strtotime($this->end_date));
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($start_date, $end_date));
        }
        
        if(!empty($this->pharmacy_status) || $this->pharmacy_status == '0'){
            $query = $query->where('users.status', $this->pharmacy_status);
        } 

        if(!empty($this->city)){
            $query = $query->whereHas('userDetails', function($q){
                $q->where('city',$this->city);
            });
        }
        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Email", "Mobile No.", "HCP Type", "Date of Joining",  "Rating",  "Status"];
    }

    public function map($data): array
    {
        $rating_count = 0;
        
        if ($this->category_id == '2') {
            if ($data->user_order_rating != '' && $data->user_order_rating != null) {
                $rating_count = $data->user_order_rating;
            }
        } else {
            if ($data->user_appointment_rating != '' && $data->user_appointment_rating != null) {
                $rating_count = $data->user_appointment_rating;
            }
        }
        
        if ($rating_count == '' || $rating_count == null) {
            $rating_count = '0';
        }
        
        $hcp_type_details = '';
        if(!empty($data->categoryParent)){
            $hcp_type_details .= $data->categoryParent->name.' '; 
        } 
        if(!empty($data->categoryChild)){
            $hcp_type_details .= $data->categoryChild->name; 
        }                            
          
        
        $status_details = "";
        if(isset($data->status)){
            if($data->status == '0'){
                $status_details = 'Active';
            }else if($data->status == '1'){
                $status_details = 'Wait for Approval';
            }else if($data->status == '2'){
                $status_details = 'Inactive';
            }else if($data->status == '3'){
                $status_details = 'Pending Verify';
            }else if($data->status == '4'){
                $status_details = 'Profile Not Complete';
            }
        }
        return [
            isset($data->user_name) ? $data->user_name : '',
            isset($data->email) ? $data->email : '',
            isset($data->mobile_no_country_code) ? $data->mobile_no_country_code : '',
            // isset($data->hcp_type) ? $data->hcp_type : '',
            !empty($data->categoryParent->name) || !empty($data->categoryChild->name)  ? $hcp_type_details : '',
            isset($data->created_at) ? Helper::getDateTimeFormate($data->created_at) : '',
            isset($data->userReview->rating) && !empty($data->userReview->rating) ? $data->userReview->rating : $rating_count,
            isset($data->status) ? $status_details : ''
        ];
    }
    
    public function columnFormats(): array
    {
        return [
  
        ];
    }
}
