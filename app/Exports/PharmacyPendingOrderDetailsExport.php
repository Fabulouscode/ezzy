<?php

namespace App\Exports;

use App\Http\Helpers\Helper;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PharmacyPendingOrderDetailsExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{

    private $start_date, $end_date, $status;
    public function __construct($start_date = '', $end_date = '', $status = '')
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status = $status;

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
        $query = Order::query()->select('orders.*')->with(['clientDetails', 'userDetails'])->where('status','0');
        // $query = $query->whereNotIn('status', [5,6]);
        if(!empty($this->start_date) && !empty($this->end_date)){
            $start_date = date('Y-m-d', strtotime($this->start_date));
            $end_date = date('Y-m-d', strtotime($this->end_date));
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($start_date, $end_date));
        }

        if(is_array($this->status) && count($this->status) > 0){
            $query = $query->whereNotIn('orders.status', $this->status);       
        }else if(isset($this->status)){
            $query = $query->where('orders.status', $this->status);
        } 

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
