<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\OrderTrackingRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\OrderRepository;
use PDF;

class OrderController extends BaseApiController
{
    private $order_repo, $shop_medicine_repo, $order_tracking_repo;

    public function __construct(
        OrderRepository $order_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderTrackingRepository $order_tracking_repo
        )
    {
        parent::__construct();
        $this->order_repo = $order_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_tracking_repo = $order_tracking_repo;
    }

    public function getOrderHistory(Request $request){
        $data = $this->order_repo->getOrderHistory($request); 
        return self::sendSuccess($data, 'Order History get');
    }
    
    public function getOrderProduct($order_id){
        $data = $this->order_repo->getbyEditId($order_id); 
        return self::sendSuccess($data, 'Order product get');
    }
  
    public function getOrderTracking($order_id){
        $data = $this->order_tracking_repo->getbyOrderId($order_id); 
        return self::sendSuccess($data, 'Order Tracking details');
    }

    public function generateInvoice($order_id){
        $medicine_types = $this->shop_medicine_repo->medicine_types;
        $delivery_type = $this->order_repo->delivery_type;
        $status = $this->order_repo->status;
        $data = $this->order_repo->getbyEditId($order_id); 
        view()->share(['data' => $data, 'status' => $status, 'delivery_type'=>$delivery_type,'medicine_types'=>$medicine_types]);
        $pdf = PDF::loadView('invoice.order', [$data, $status, $delivery_type, $medicine_types]);
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
  
    public function changeOrderStatus(OrderStatusRequest $request){
        $data = array();
        $update = [
                    'status'=> $request->status,
                    'cancel_reason'=> !empty($request->cancel_reason) && $request->status == '6' ? $request->cancel_reason : NULL,
                    'cancel_date'=> !empty($request->cancel_date) && $request->status == '6' ? $request->cancel_date : NULL,
                    'cancel_user_id'=> !empty($request->cancel_date) && $request->status == '6' ? $request->user()->id : NULL,
                  ];

        try{
            $this->order_repo->update($update, $request->id);
            $data = $this->order_repo->getById($request->id);
            return self::sendSuccess($data, 'Order status change');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
}
