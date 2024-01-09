<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
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

class UserApprovedPayoutExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{
    private $category_id;

    public function __construct($category_id = '')
    {
        $this->category_id = $category_id;
    }


    public function styles(Worksheet $sheet)
    {
        return [

            1    => ['font' => ['bold' => true]],
        ];
    }

    public function query()
    {
        $query = User_transaction::query()->with(['client'])->select('id', 'client_id', 'payout_status')
            ->addSelect(DB::raw('sum(user_transactions.payout_amount) as payout_total'))
            ->addSelect(DB::raw('sum(user_transactions.amount) as amount'))
            ->addSelect(DB::raw('sum(user_transactions.fees_charge) as fees_charge'));

        $query = $query->whereHas('client', function ($query) {
            $query->whereNotNull('category_id');
        });
        $query = $query->where('payout_status', '=', '0');

        $query = $query->where('status', '0')->groupBy('client_id', 'payout_status')->orderBy('id', 'desc');


        if (!empty($this->category_id)) {
            $category = $this->category_id;
            $query = $query->whereHas('client', function ($query) use ($category) {
                $query->where('category_id', $category);
            });
        }
        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider",  "Amount", "Deduction", "Payout Amount"];
    }

    public function map($data): array
    {
        $service_provider_details = '';
        if (!empty($data->client->categoryParent)) {
            $service_provider_details .= $data->client->categoryParent->name . ' ';
        }
        if (!empty($data->client->categoryChild)) {
            $service_provider_details .= $data->client->categoryChild->name;
        }
        return [
            !empty($data->client) ? $data->client->user_name : '-',
            !empty($data->client) && !empty($data->client->categoryParent) && !empty($data->client->categoryChild) ? $service_provider_details : '',
            isset($data->amount) ? $data->amount : '0',
            isset($data->fees_charge) ? $data->fees_charge : '0',
            isset($data->payout_total) ? $data->payout_total : '0',

        ];
    }

    public function columnFormats(): array
    {
        return [];
    }
}
