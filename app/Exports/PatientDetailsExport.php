<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Medicine_category;
use App\Models\Medicine_details;
use App\Models\User;
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

class PatientDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{

    private $category_id;
    private $subcategory_id;
    private $status;
    public function __construct($category_id, $subcategory_id, array $status = [])
    {
        $this->category_id = $category_id;
        $this->subcategory_id = $subcategory_id;
        $this->status = $status;
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
