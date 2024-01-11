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
use phpDocumentor\Reflection\Types\This;

class UserPayoutDepositTransactionListExport implements FromQuery, WithHeadings, WithColumnFormatting, WithMapping, WithStyles
{
    public $start_date, $end_date;
    public $currency_symbol = '₦ ';
    public function __construct($start_date='',$end_date='')
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function styles(Worksheet $sheet)
    {
        return [

            1    => ['font' => ['bold' => true]],


        ];
    }

    public function query()
    {
        $query = User_transaction::with(['users']);
       
        $query = $query->whereNull('client_id')
            ->select('user_transactions.*')
            ->leftJoin('users', 'user_transactions.user_id', '=', 'users.id')
            ->where('transaction_msg','Wallet Topup');

        if(!empty($this->start_date) && !empty($this->end_date)){
            $query = $query->whereDate('user_transactions.transaction_date', '>=',$this->start_date)->whereDate('user_transactions.transaction_date' , '<=',$this->end_date);
        }
        
        $query = $query->where('user_transactions.mode_of_payment', '0')->where('user_transactions.status', '0');

            
        return $query;
    }

    public function headings(): array
    {
        return ["User Name",  "Transaction Msg", "Transaction Date", "Transaction Type", "Deposit Amount", "Status"];
    }

    public function map($data): array
    {
        $transaction_type = '';
        if($data->transaction_type == '0'){
            if($data->mode_of_payment == '0'){
                $transaction_type =  "Credit";
            } else{
                $transaction_type =  "Debit";
            }
        }
        $status_details ='';
        if($data->status == '0'){
            $status_details = $data->status_name;
        } else{
            $status_details = $data->status_name;
        }
        return [
            !empty($data->users) ? $data->users->user_name : '-',
            isset($data->transaction_msg) ? 'Add in Wallet' : '',
            isset($data->transaction_date) ? Helper::getDateTimeFormate($data->transaction_date) : '',
            !empty($transaction_type)?$transaction_type :'',
            isset($data->amount) ? $this->currency_symbol . ' ' . $data->amount : '0',
            !empty($data->status_name)?$status_details:'',

        ];
    }

    public function columnFormats(): array
    {
        return [
        
        ];
    }
}
