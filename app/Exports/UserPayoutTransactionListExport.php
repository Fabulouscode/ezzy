<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\User_transaction;
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

class UserPayoutTransactionListExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{
    // private $category_id;
    public $currency_symbol = '₦ ';
    public function __construct()
    {
        // $this->category_id = $category_id;
    }


    public function styles(Worksheet $sheet)
    {
        return [

            1    => ['font' => ['bold' => true]],


        ];
    }

    public function query()
    {
        $query = User_transaction::with(['users', 'client']);

        $query = $query->whereNotNull('client_id')
            ->select('user_transactions.*')
            ->leftJoin('users', 'user_transactions.user_id', '=', 'users.id')
            ->leftJoin('users as client', 'user_transactions.client_id', '=', 'client.id');


        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider",  "Transaction Msg", "Transaction Date", "Fees Charge", "Payout Amount", "Total Charge"];
    }

    public function map($data): array
    {
        return [
            !empty($data->users) ? $data->users->user_name : '-',
            !empty($data->client)   ? $data->client->user_name : '',
            isset($data->transaction_msg) ? $data->transaction_msg : '',
            isset($data->transaction_date) ? Helper::getDateTimeFormate($data->amount) : '',
            isset($data->fees_charge) ? $this->currency_symbol . ' ' . $data->fees_charge : '0',
            isset($data->payout_amount) ? $this->currency_symbol . ' ' . $data->payout_amount : '0',
            isset($data->amount) ? $this->currency_symbol . ' ' . $data->amount : '0',

        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'B' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
