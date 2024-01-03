<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Appointment;
use App\Models\Medicine_category;
use App\Models\Medicine_details;
use App\Models\Order;
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

class PharmacyOrderDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{


    public function __construct()
    {
    }

    public function getFilename()
    {
        return 'pharmacy_order_details';
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
        $query = Order::query()->select('orders.*')->with(['clientDetails', 'userDetails']);
        // $query = $query->whereNotIn('status', [5,6]);

        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider Name", "Created Date",   "Status"];
    }

    public function map($data): array
    {
        $status = '';
        if ($data->status == '0') {
            $status .= $data->status_name;
        } else if ($data->status == '1' || $data->status == '2') {
            $status .= $data->status_name;
        } else if ($data->status == '3') {
            $status .= $data->status_name;
        } else if ($data->status == '4') {
            $status .= $data->status_name;
        } else {
            $status .= $data->status_name;
        }
        return [

            !empty($data->clientDetails) && !empty($data->clientDetails->user_name) ? $data->clientDetails->user_name : '',
            !empty($data->userDetails) && !empty($data->userDetails->user_name) ? $data->userDetails->user_name : '',
            isset($data->created_at)  ? Helper::getDateTimeFormate($data->created_at) : '',
            isset($data->status) ? $status : ''

        ];
    }

    public function columnFormats(): array
    {
        return [];
    }
}
