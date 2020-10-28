<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use PDF;

class OrderController extends BaseApiController
{
    public function getOrderHistory(Request $request){
        $data = $this->order_repo->getOrderHistory($request); 
        return self::sendSuccess($data, 'Order History get');
    }
    
    public function getOrderProduct($order_id){
        $data = $this->order_repo->getbyEditId($order_id); 
        return self::sendSuccess($data, 'Order product get');
    }

    public function generateInvoice($order_id){
        $data = $this->order_repo->getbyEditId($order_id); 
        view()->share('data',$data);
        $pdf = PDF::loadView('invoice.order', $data);
        $pdf_file = $this->order_repo->uploadPDFFile($pdf->output(), 'pdf/order_invoice'); 
        $file_url = url('storage/'.$pdf_file);
        return self::sendSuccess($file_url, 'Order Invoice get');
    }


    public function getCompletedOrder(Request $request){
        $data = array();
        $data['status'] = $this->order_repo->status;
        $data['delivery_type'] = $this->order_repo->delivery_type;
        $data['result'] = $this->order_repo->getCompletedOrder($request);
        return self::sendSuccess($data);
    }

    public function getCancelledOrder(Request $request){
        $data = array();
        $data['status'] = $this->order_repo->status;
        $data['delivery_type'] = $this->order_repo->delivery_type;
        $data['result'] = $this->order_repo->getCancelledOrder($request);
        return self::sendSuccess($data);
    }
   
    public function getActiveOrder(Request $request){
        $data = array();
        $data['status'] = $this->order_repo->status;
        $data['delivery_type'] = $this->order_repo->delivery_type;
        $data['result'] = $this->order_repo->getActiveOrder($request);
        return self::sendSuccess($data);
    }
  
    public function changeOrderStatus(Request $request){
        $data = array();
        $update = [
                    'status'=> $request->status,
                    'cancel_reason'=> !empty($request->cancel_reason) && $request->status == '6' ? $request->cancel_reason : NULL,
                    'cancel_date'=> !empty($request->cancel_date) && $request->status == '6' ? $request->cancel_date : NULL,
                    'cancel_user_id'=> !empty($request->cancel_date) && $request->status == '6' ? $request->user()->id : NULL,
                  ];
        $this->appointment_repo->update($update, $request->id);
        $data = $this->appointment_repo->getById($request->id);
        return self::sendSuccess($data);
    }
}
