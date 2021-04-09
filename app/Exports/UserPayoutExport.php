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

class UserPayoutExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles {
    private $status;

    public function __construct(int $status) 
    {
        $this->status = $status;
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
        $query = User_transaction::query()->with(['client'])->select('id','client_id')
        ->addSelect(DB::raw('sum(user_transactions.payout_amount) as payout_total'))
        ->addSelect(DB::raw('sum(user_transactions.amount) as amount'))
        ->addSelect(DB::raw('sum(user_transactions.fees_charge) as fees_charge'));
      
        if(isset($this->status)){
            $query = $query->where('payout_status',$this->status);
        }
        
        $query = $query->where('status', '0')->groupBy('client_id')->orderBy('id','desc');

        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider", "Bank Details", "Amount", "Deduction", "Payout Amount"];
    }

    public function map($data): array
    {

        $bank_details = "";
        if(!empty($data->client) && !empty($data->client->userPrimaryBankAccount)){
            $bank_details = 'Bank Name: '.$data->client->userPrimaryBankAccount->bank_name.', ';
            $bank_details .= 'Account Name: '.$data->client->userPrimaryBankAccount->name.', ';
            $bank_details .= 'Account No.: '.$data->client->userPrimaryBankAccount->account_number;
        } 
          
        return [
            !empty($data->client) ? $data->client->user_name : '-',            
            !empty($data->client) && !empty($data->client->categoryParent) ? $data->client->categoryParent->name : '-',
            isset($bank_details) ? $bank_details : '',
            isset($data->amount) ? $data->amount : '0',
            isset($data->fees_charge) ? $data->fees_charge : '0',
            isset($data->payout_total) ? $data->payout_total : '0'
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