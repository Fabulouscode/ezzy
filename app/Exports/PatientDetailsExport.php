<?php

namespace App\Exports;

use App\Http\Helpers\Helper;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB as FacadesDB;

class PatientDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{

    private $category_id, $subcategory_id, $status, $start_date, $end_date, $patient_status, $birth_start_date, $birth_end_date, $dob_by_month, $dob_by_year;
    public function __construct($category_id, $subcategory_id, array $status = [], $start_date = '', $end_date = '', $patient_status = '', $birth_start_date = '', $birth_end_date = '', $dob_by_month = '', $dob_by_year = '')
    {
        $this->category_id = $category_id;
        $this->subcategory_id = $subcategory_id;
        $this->status = $status;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->patient_status = $patient_status;
        $this->birth_start_date = $birth_start_date;
        $this->birth_end_date = $birth_end_date;
        $this->dob_by_month = $dob_by_month;
        $this->dob_by_year = $dob_by_year;
    }

    public function getFilename()
    {
        return 'patient_details';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function query()
    {
        $query = User::query()->select('users.*')->with(['categoryChild', 'categoryParent', 'userDetails', 'userReview']);
        if (!empty($this->category_id)) {
            $category = $this->category_id;
            $query = $query->whereHas('categoryParent', function ($query) use ($category) {
                $query->where('parent_id', $category);
            });

            if (!empty($this->subcategory_id)) {
                $query = $query->where('users.category_id', $this->subcategory_id);
            }

            if (is_array($this->status)) {
                $query = $query->whereIn('users.status', $this->status);
            } else {
                $query = $query->where('users.status', $this->status);
            }
        } else {
            $query = $query->whereNull('users.category_id');
            $query = $query->whereNull('users.subcategory_id');
        }

        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime(($this->start_date)));
            $end_date = date('Y-m-d', strtotime($this->end_date));
            $query = $query->whereBetween(FacadesDB::raw('DATE(created_at)'), array($start_date, $end_date));
        }


        if(!empty($this->patient_status) || $this->patient_status == '0'){
            $query = $query->where('users.status', $this->patient_status);
        } 

        if (!empty($this->birth_start_date) && !empty($this->birth_end_date)) {
            $query->whereHas('userDetails', function($q) {
                $q->whereDate('dob', '>=', $this->birth_start_date)
                  ->whereDate('dob', '<=', $this->birth_end_date);
            });
        }

        if (!empty($this->dob_by_month)) {
            $query->whereHas('userDetails', function($q) {
                $q->whereMonth('dob',$this->dob_by_month);
            });
        }

        if (!empty($this->dob_by_year)) {
            $query->whereHas('userDetails', function($q) {
                $q->whereYear('dob',$this->dob_by_year);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Email", "Mobile No.", "Wallet Amount", "Date of Joining",  "Date of Birth", "Rating", "Status"];
    }

    public function map($data): array
    {
        $rating_count = 0;



        if ($rating_count == '' || $rating_count == null) {
            $rating_count = '0';
        }

        $status_details = "";
        if ($data->status == '2') {
            $status_details .= $data->status_name;
        } else if ($data->status == '1') {
            $status_details .= $data->status_name;
        } else {
            $status_details .= $data->status_name;
        }
        return [
            isset($data->user_name) ? $data->user_name : '',
            isset($data->email) ? $data->email : '',
            isset($data->mobile_no_country_code) ? $data->mobile_no_country_code : '',
            isset($data->wallet_balance) ? $data->wallet_balance : '',
            isset($data->created_at) ? Helper::getDateTimeFormate($data->created_at) : '',
            !empty($data->userDetails) && !empty($data->userDetails->dob) ? Helper::getDateTimeFormate($data->userDetails->dob) : '',
            isset($data->userReview->rating) && !empty($data->userReview->rating) ? $data->userReview->rating : $rating_count,
            isset($data->status) ? $status_details : ''
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }
}
