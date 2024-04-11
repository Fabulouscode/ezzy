<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Appointment;
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

class AppointmentUpcomingDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{


    public function __construct()
    {
    }

    public function getFilename()
    {
        return 'appointment_upcoming_details';
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
        $query = Appointment::query()->select('appointments.*')->with(['user', 'client', 'user.categoryParent', 'user.categoryChild']);
        $query = $query->whereNotIn('status', [5, 6]);

        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider Name", "HCP Type", "Appointment Type",  "Start Date Time",  "Status"];
    }

    public function map($data): array
    {


        $hcp_type = '';
        if (!empty($data->user->categoryParent)) {
            $hcp_type .= $data->user->categoryParent->name;
        }
        if (!empty($data->user->categoryChild)) {
            $hcp_type .= $data->user->categoryChild->name;
        }

        $appointment_type = '';
        if ($data->appointment_type == '2') {
            $appointment_type .= $data->appointment_type_name;
        } else if ($data->appointment_type == '1') {
            $appointment_type .= $data->appointment_type_name;
        } else {
            $appointment_type .= $data->appointment_type_name;
        }

        $status = '';
        if ($data->status == '0') {
            $status .= $data->status_name;
        } else if ($data->status == '1') {
            $status .= $data->status_name;
        } else if ($data->status == '2') {
            $status .= $data->status_name;
        } else if ($data->status == '3') {
            $status .= $data->status_name;
        } else if ($data->status == '4') {
            $status .= $data->status_name;
        } else if ($data->status == '5') {
            $status .= $data->status_name;
        } else if ($data->status == '6') {
            $status .= $data->status_name;
        }
        return [

            !empty($data->client) && !empty($data->client->user_name) ? $data->client->user_name : '',
            !empty($data->user) && !empty($data->user->user_name) ? $data->user->user_name : '',
            !empty($data->user->categoryParent->name) || !empty($data->user->categoryChild->name)   ? $hcp_type : '',
            isset($data->appointment_type) ? $appointment_type : '',
            isset($data->appointment_date) && isset($data->appointment_time) ? Helper::getDateTimeFormate($data->appointment_date . ' ' . $data->appointment_time) : '',
            isset($data->status) ? $status : ''

        ];
    }

    public function columnFormats(): array
    {
        return [];
    }
}
