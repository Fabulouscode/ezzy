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
    private $category_id, $start_date, $end_date, $transaction_msg;
    public $currency_symbol = '₦ ';
    public function __construct($category_id='', $start_date='', $end_date='', $transaction_msg='')
    {
        $this->category_id = $category_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->transaction_msg = $transaction_msg;
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

            if(!empty($this->category_id)){
                $category = $this->category_id;
                $query = $query->whereHas('client',function($query) use($category){
                    $query->where('category_id',$category);
                });
            }
            if(!empty($this->start_date) && !empty($this->end_date)){
                $query = $query->whereDate('transaction_date', '>=',$this->start_date)->whereDate('transaction_date' , '<=',$this->end_date);
            }

            if (strpos($this->transaction_msg, '_') !== false) {
            
                $explodedArray = explode("_", $this->transaction_msg);
                $resultString = implode(" ", $explodedArray);
                
                if(!empty($resultString)){
                    $query = $query->where('user_transactions.transaction_msg', $resultString);
                }
                
            } else {
                if($this->transaction_msg){
    
                    $query = $query->where('user_transactions.transaction_msg',$this->transaction_msg);
                }
            }
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
            isset($data->transaction_date) ? Helper::getDateTimeFormate($data->transaction_date) : '',
            isset($data->fees_charge) ? $this->currency_symbol . ' ' . $data->fees_charge : '0',
            isset($data->payout_amount) ? $this->currency_symbol . ' ' . $data->payout_amount : '0',
            isset($data->amount) ? $this->currency_symbol . ' ' . $data->amount : '0',

        ];
    }

    public function columnFormats(): array
    {
        return [
                       
        ];
    }
}
