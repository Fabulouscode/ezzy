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
    private $user_ids;
    private $hcp_type;
    public function __construct( $user_ids = [], $hcp_type = '') 
    {
        $this->user_ids = $user_ids;
        $this->hcp_type = $hcp_type;
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
        $query = User_transaction::query()->with(['client'])->select('id','client_id','payout_status')
        ->addSelect(DB::raw('sum(user_transactions.payout_amount) as payout_total'))
        ->addSelect(DB::raw('sum(user_transactions.amount) as amount'))
        ->addSelect(DB::raw('sum(user_transactions.fees_charge) as fees_charge'));
      
        // if(!empty($this->status)){
        //     $query = $query->where('payout_status',$this->status);
        // }
        if(!empty($this->user_ids) && count($this->user_ids) > 0){
            $query = $query->whereIn('client_id',$this->user_ids);
        }

        $query = $query->whereHas('client', function ($query){
            $query->whereNotNull('category_id');
        });
        $query = $query->where('payout_status', '!=', '0');
        
        $query = $query->where('status', '0')->groupBy('client_id','payout_status')->orderBy('id','desc');
        if (!empty($this->hcp_type)) {
            $category = $this->hcp_type;
            $query = $query->whereHas('client', function ($query) use ($category) {
                $query->where('category_id', $category);
            });
        }
        return $query;
    }

    public function headings(): array
    {
        return ["User Name", "Service Provider", "Bank Details", "Amount", "Deduction", "Payout Amount", "Status"];
    }

    public function map($data): array
    {

        $bank_details = "";
        if(!empty($data->client) && !empty($data->client->userPrimaryBankAccount)){
            $bank_details = 'Bank Name: '.$data->client->userPrimaryBankAccount->bank_name.', ';
            $bank_details .= 'Account Name: '.$data->client->userPrimaryBankAccount->name.', ';
            $bank_details .= 'Account No.: '.$data->client->userPrimaryBankAccount->account_number;
        } 
        $status_details = "";
        if(isset($data->payout_status)){
            if($data->payout_status == '0'){
                $status_details = 'Paid';
            }else if($data->payout_status == '1'){
                $status_details = 'Pending';
            }else if($data->payout_status == '2'){
                $status_details = 'Cancel';
            }else if($data->payout_status == '3'){
                $status_details = 'In-progress';
            }
        } 
          
        return [
            !empty($data->client) ? $data->client->user_name : '-',            
            !empty($data->client) && !empty($data->client->categoryParent) ? $data->client->categoryParent->name : '-',
            isset($bank_details) ? $bank_details : '',
            isset($data->amount) ? $data->amount : '0',
            isset($data->fees_charge) ? $data->fees_charge : '0',
            isset($data->payout_total) ? $data->payout_total : '0',
            isset($data->payout_status) ? $status_details : ''
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