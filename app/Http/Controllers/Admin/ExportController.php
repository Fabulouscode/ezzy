<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AppointmentCancelDetailsExport;
use App\Exports\AppointmentCompletedDetailsExport;
use App\Exports\AppointmentUpcomingDetailsExport;
use App\Exports\ApprovedHCPDetailsExport;
use App\Exports\ApprovedLaboratoriesDetailsExport;
use App\Exports\ApprovedPharmacistDetailsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PatientDetailsExport;
use App\Exports\PendingHCPDetailsExport;
use App\Exports\PendingLaboratoriesDetailsExport;
use App\Exports\PendingPharmacistDetailsExport;
use App\Exports\PharmacyOrderDetailsExport;
use App\Exports\UserApprovedPayoutExport;
use App\Exports\UserPayoutDepositTransactionListExport;
use App\Exports\UserPayoutTransactionListExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportPatient(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();

        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $patient_file = Excel::raw(new PatientDetailsExport('', '', [0, 2], $start, $end), \Maatwebsite\Excel\Excel::XLSX);
        }else{

            $patient_file = Excel::raw(new PatientDetailsExport('', '', [0, 2], '', '',  $request->filter_status, $request->birth_start_date, $request->birth_end_date, $request->dob_month, $request->dob_year), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "patient_details",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($patient_file)
        );
        $notification_msg = 'Patient details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportPendingHCPExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();
        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $pending_hcp_file = Excel::raw(new PendingHCPDetailsExport(1, '', 1, $start, $end, $request->subcategory_id, $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $pending_hcp_file = Excel::raw(new PendingHCPDetailsExport(1, '', 1, '', '', $request->subcategory_id, $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "pending_hcp_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pending_hcp_file) 
        );
        $notification_msg = 'Pending HCP details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportApprovedHCPExcel(Request $request)
    {
        $approved_hcp_file = Excel::raw(new ApprovedHCPDetailsExport(1, '', [0, 2], $request->user_start_date, $request->user_end_date, $request->user_approved_start_date, $request->user_approved_end_date, $request->subcategory_id, $request->filter_status, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "approved_hcp_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($approved_hcp_file) 
        );
        $notification_msg = 'Approved HCP details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportPendingPharmacistExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();

        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $pending_pharma_file = Excel::raw(new PendingPharmacistDetailsExport(2, '', 1, $start, $end, $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $pending_pharma_file = Excel::raw(new PendingPharmacistDetailsExport(2, '', 1, '', '', $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "pending_pharma_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pending_pharma_file) 
        );
        $notification_msg = 'Pending Pharmacist details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportApprovedPharmacistExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();

        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $approved_pharma_file = Excel::raw(new ApprovedPharmacistDetailsExport(2, '', [0, 2], $start, $end, $request->filter_status, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $approved_pharma_file = Excel::raw(new ApprovedPharmacistDetailsExport(2, '', [0, 2], '', '', $request->filter_status, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "approved_pharma_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($approved_pharma_file) 
        );
        $notification_msg = 'Approved Pharmacist details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportPendingLaboratoriesExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();

        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $pending_lab_file = Excel::raw(new PendingLaboratoriesDetailsExport(3, '', 1, $start, $end, $request->subcategory_id, $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $pending_lab_file = Excel::raw(new PendingLaboratoriesDetailsExport(3, '', 1, '', '', $request->subcategory_id, $request->completed_progress, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "pending_lab_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pending_lab_file) 
        );
        $notification_msg = 'Pending Laboratories details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportApprovedLaboratoriesExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();
        if(!empty($request->date_range)){
            $date_range = explode(' -', $request->date_range);
            if($date_range){
                $start = date('Y-m-d', strtotime($date_range[0]));
                $end = date('Y-m-d', strtotime($date_range[1]));
            }
            $approved_lab_file = Excel::raw(new ApprovedLaboratoriesDetailsExport(3, '', [0, 2], $start, $end, $request->subcategory_id, $request->filter_status, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $approved_lab_file = Excel::raw(new ApprovedLaboratoriesDetailsExport(3, '', [0, 2], '', '', $request->subcategory_id, $request->filter_status, $request->city_id), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "approved_lab_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($approved_lab_file) 
        );
        $notification_msg = 'Approved Laboratories details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportAppointmentUpcomingExcel(Request $request)
    {
        $appointment_upcoming_file = Excel::raw(new AppointmentUpcomingDetailsExport($request->category_id, $request->appointment_type, $request->urgent, $request->filter_status, $request->start_date, $request->end_date), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "appointment_upcoming_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($appointment_upcoming_file) 
        );
        $notification_msg = 'Appointment Upcoming details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportAppointmentCompletedExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();
        if(!empty($request->appointment_created_date_range)){
            $appointment_created_date_range = explode(' -', $request->appointment_created_date_range);
            if($appointment_created_date_range){
                $start = date('Y-m-d', strtotime($appointment_created_date_range[0]));
                $end = date('Y-m-d', strtotime($appointment_created_date_range[1]));
            }
            $appointment_completed_file = Excel::raw(new AppointmentCompletedDetailsExport($start, $end, $request->appointment_completed_start_date, $request->appointment_completed_end_date, $request->category_id, $request->appointment_type, $request->urgent), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $appointment_completed_file = Excel::raw(new AppointmentCompletedDetailsExport('', '', $request->appointment_completed_start_date, $request->appointment_completed_end_date, $request->category_id, $request->appointment_type, $request->urgent), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "appointment_completed_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($appointment_completed_file) 
        );
        $notification_msg = 'Appointment Completed details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function exportAppointmentCancelExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();
        if(!empty($request->appointment_date_range)){
            $appointment_date_range = explode(' -', $request->appointment_date_range);
            if($appointment_date_range){
                $start = date('Y-m-d', strtotime($appointment_date_range[0]));
                $end = date('Y-m-d', strtotime($appointment_date_range[1]));
            }
            $appointment_cancel_file = Excel::raw(new AppointmentCancelDetailsExport($start, $end, $request->category_id, $request->appointment_type, $request->urgent), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $appointment_cancel_file = Excel::raw(new AppointmentCancelDetailsExport('', '', $request->category_id, $request->appointment_type, $request->urgent), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "appointment_cancel_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($appointment_cancel_file) 
        );
        $notification_msg = 'Appointment Cancel details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function pharmacyOrderExportExcel(Request $request)
    {
        $start = new Carbon();
        $end = new Carbon();
        if(!empty($request->order_date_range)){
            $order_date_range = explode(' -',$request->order_date_range);
            if($order_date_range){
                $start = date('Y-m-d', strtotime($order_date_range[0]));
                $end = date('Y-m-d', strtotime($order_date_range[1]));
            }
            $pharmacy_order_file = Excel::raw(new PharmacyOrderDetailsExport($start, $end, $request->status), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $pharmacy_order_file = Excel::raw(new PharmacyOrderDetailsExport('', '', $request->status), \Maatwebsite\Excel\Excel::XLSX);
        }
        $response =  array(
            'name' => "pharmacy_order_details", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($pharmacy_order_file) 
        );
        $notification_msg = 'Pharmacy Order details export successfully.';
        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function getApprovedPayoutExport(Request $request)
    {
        if (!empty($request->category_id)) {
            $approved_payout_file = Excel::raw(new UserApprovedPayoutExport($request->category_id), \Maatwebsite\Excel\Excel::XLSX);
        } else {
            $approved_payout_file = Excel::raw(new UserApprovedPayoutExport, \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "approved_payout_users", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($approved_payout_file) 
        );

        $notification_msg = 'Approved Payout success';

        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function getPayoutTransactionListExport(Request $request)
    {
        
        if (!empty($request->category_id) || !empty($request->start_date) || !empty($request->end_date) || !empty($request->transaction_msg)) {
            $payout_transaction_file = Excel::raw(new UserPayoutTransactionListExport($request->category_id, $request->start_date, $request->end_date, $request->transaction_msg), \Maatwebsite\Excel\Excel::XLSX);
        } else {
            $payout_transaction_file = Excel::raw(new UserPayoutTransactionListExport, \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "payout_transaction_list", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($payout_transaction_file) 
        );

        $notification_msg = 'Payout Transaction List success';

        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }

    public function getPayoutDepositTransactionListExport(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {

            $payout_deposit_transaction_file = Excel::raw(new UserPayoutDepositTransactionListExport($request->start_date, $request->end_date), \Maatwebsite\Excel\Excel::XLSX);
        } else {
            $payout_deposit_transaction_file = Excel::raw(new UserPayoutDepositTransactionListExport, \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "payout_deposit_transaction_list", 
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($payout_deposit_transaction_file) 
        );

        $notification_msg = 'Payout Deposit Transaction List success';

        return response()->json(['data' => $response, 'msg' => $notification_msg], 200);
    }
}
