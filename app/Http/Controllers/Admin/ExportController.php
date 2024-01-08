<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AppointmentCancelDetailsExport;
use App\Exports\AppointmentCompletedDetailsExport;
use App\Exports\AppointmentUpcomingDetailsExport;
use App\Exports\ApprovedHCPDetailsExport;
use App\Exports\ApprovedLaboratoriesDetailsExport;
use App\Exports\ApprovedPharmacistDetailsExport;
use App\Exports\CompletedAppointmentDetailsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PatientDetailsExport;
use App\Exports\PendingHCPDetailsExport;
use App\Exports\PendingLaboratoriesDetailsExport;
use App\Exports\PendingPharmacistDetailsExport;
use App\Exports\PharmacyOrderDetailsExport;
use App\Exports\UserApprovedPayoutExport;
use App\Exports\UserPayoutTransactionListExport;
use Maatwebsite\Excel\Facades\Excel;
use Svg\Tag\Rect;

class ExportController extends Controller
{
    public function exportPatient(Request $request)
    {
        $patient_file = Excel::raw(new PatientDetailsExport('','',[0,2]), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "patient_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($patient_file) //mime type of used format
        );
        $notification_msg = 'Patient details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportPendingHCPExcel(){
        $pending_hcp_file = Excel::raw(new PendingHCPDetailsExport(1,'',1), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "pending_hcp_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($pending_hcp_file) //mime type of used format
        );
        $notification_msg = 'Pending HCP details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportApprovedHCPExcel(){
        $approved_hcp_file = Excel::raw(new ApprovedHCPDetailsExport(1,'',[0,2]), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "approved_hcp_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($approved_hcp_file) //mime type of used format
        );
        $notification_msg = 'Approved HCP details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportPendingPharmacistExcel(){
        $pending_pharma_file = Excel::raw(new PendingPharmacistDetailsExport(2,'',1), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "pending_pharma_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($pending_pharma_file) //mime type of used format
        );
        $notification_msg = 'Pending Pharmacist details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportApprovedPharmacistExcel(Request $request){
        $approved_pharma_file = Excel::raw(new ApprovedPharmacistDetailsExport(2,'',[0,2]), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "approved_pharma_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($approved_pharma_file) //mime type of used format
        );
        $notification_msg = 'Approved Pharmacist details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportPendingLaboratoriesExcel(Request $request){
        $pending_lab_file = Excel::raw(new PendingLaboratoriesDetailsExport(3,'',1), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "pending_lab_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($pending_lab_file) //mime type of used format
        );
        $notification_msg = 'Pending Laboratories details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);        
    }

    public function exportApprovedLaboratoriesExcel(){
        $approved_lab_file = Excel::raw(new ApprovedLaboratoriesDetailsExport(3,'',[0,2]), \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "approved_lab_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($approved_lab_file) //mime type of used format
        );
        $notification_msg = 'Approved Laboratories details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportAppointmentUpcomingExcel(){
        $appointment_upcoming_file = Excel::raw(new AppointmentUpcomingDetailsExport, \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "appointment_upcoming_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($appointment_upcoming_file) //mime type of used format
        );
        $notification_msg = 'Appointment Upcoming details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportAppointmentCompletedExcel(){
        $appointment_completed_file = Excel::raw(new AppointmentCompletedDetailsExport, \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "appointment_completed_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($appointment_completed_file) //mime type of used format
        );
        $notification_msg = 'Appointment Completed details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function exportAppointmentCancelExcel(){
        $appointment_cancel_file = Excel::raw(new AppointmentCancelDetailsExport, \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "appointment_cancel_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($appointment_cancel_file) //mime type of used format
        );
        $notification_msg = 'Appointment Cancel details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function pharmacyOrderExportExcel(){
        $pharmacy_order_file = Excel::raw(new PharmacyOrderDetailsExport, \Maatwebsite\Excel\Excel::XLSX);
        $response =  array(
            'name' => "pharmacy_order_details", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($pharmacy_order_file) //mime type of used format
        );
        $notification_msg = 'Pharmacy Order details export successfully.';
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    public function getApprovedPayoutExport(Request $request){
        // dd($request->category_id);
        if(!empty($request->category_id)){
            $approved_payout_file = Excel::raw(new UserApprovedPayoutExport($request->category_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $approved_payout_file = Excel::raw(new UserApprovedPayoutExport, \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "approved_payout_users", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($approved_payout_file) //mime type of used format
        );
            
        $notification_msg = 'Approved Payout success';
    
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);        
    }

    public function getPayoutTransactionListExport(Request $request){
        // dd($request->category_id);
        if(!empty($request->category_id)){
            $payout_transaction_file = Excel::raw(new UserPayoutTransactionListExport, \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $payout_transaction_file = Excel::raw(new UserPayoutTransactionListExport, \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "payout_transaction_list", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($payout_transaction_file) //mime type of used format
        );
            
        $notification_msg = 'Payout Transaction List success';
    
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);        
    }
}
