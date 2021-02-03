<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\OrderTrackingRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ShoppingCartRepository;
use App\Repositories\VoucherCodeRepository;
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends BaseApiController
{
    private $order_repo, $user_repo, $voucher_code_repo, $shop_medicine_repo, $order_tracking_repo, $shop_cart_repo, $order_product_repo, $notification_repo;

    public function __construct(
        ShoppingCartRepository $shop_cart_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderTrackingRepository $order_tracking_repo,
        NotificationRepository $notification_repo,
        UserRepository $user_repo,
        VoucherCodeRepository $voucher_code_repo
        )
    {
        parent::__construct();
        $this->shop_cart_repo = $shop_cart_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_tracking_repo = $order_tracking_repo;
        $this->notification_repo = $notification_repo;
        $this->user_repo = $user_repo;
        $this->voucher_code_repo = $voucher_code_repo;
    }

    public function getOrderHistory(Request $request)
    {
        $data = $this->order_repo->getOrderHistory($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'completed_datetime'=>$response->completed_datetime,
                                        'cancel_reason'=>$response->cancel_reason,
                                        'cancel_date'=>$response->cancel_date,
                                        'created_at'=>$response->created_at,
                                        'order_medicine_name'=> !empty($response->order_medicine_name) ? $response->order_medicine_name : '',
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->userDetails))?
                                                        [
                                                            'id'=>$response->userDetails->id,
                                                            'user_name'=>$response->userDetails->user_name,
                                                            'profile_image'=>$response->userDetails->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                }); 
        return self::sendSuccess($data, 'Order History get');
    }
    
    public function getOrderProduct($order_id)
    {
        $data = $this->order_repo->getbyEditId($order_id)->format();
        return self::sendSuccess($data, 'Order product get');
    }
  
    public function getOrderTracking($order_id)
    {
        $data = $this->order_tracking_repo->getbyOrderId($order_id); 
        return self::sendSuccess($data, 'Order Tracking details');
    }

    public function generateInvoice($order_id)
    {
        $medicine_types = $this->shop_medicine_repo->getMedicineTypeValue();
        $currency_symbol  = $this->order_repo->currency_symbol;
        $delivery_type = $this->order_repo->getDeliveryTypeValue();
        $status = $this->order_repo->getStatusValue();
        $data = $this->order_repo->getbyEditId($order_id); 
        view()->share(['data' => $data, 'status' => $status,'currency_symbol' => $currency_symbol, 'delivery_type'=>$delivery_type,'medicine_types'=>$medicine_types]);
        //  return view('invoice.order');
        $pdf = PDF::loadView('invoice.order', [$data, $status, $delivery_type, $medicine_types]);
        $pdf_file = $this->order_repo->uploadPDFFile($pdf->output(), 'pdf/order_invoice'); 
        $file_url = url('storage/'.$pdf_file);
        return self::sendSuccess($file_url, 'Order Invoice get');
    }


    public function getCompletedOrder(Request $request)
    {
        $data = array();
        $data = $this->order_repo->getCompletedOrder($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'completed_datetime'=>$response->completed_datetime,
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->userDetails))?
                                                        [
                                                            'id'=>$response->userDetails->id,
                                                            'user_name'=>$response->userDetails->user_name,
                                                            'profile_image'=>$response->userDetails->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getCancelledOrder(Request $request)
    {
        $data = array();
        $data = $this->order_repo->getCancelledOrder($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'cancel_reason'=>$response->cancel_reason,
                                        'cancel_date'=>$response->cancel_date,
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->userDetails))?
                                                        [
                                                            'id'=>$response->userDetails->id,
                                                            'user_name'=>$response->userDetails->user_name,
                                                            'profile_image'=>$response->userDetails->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }
   
    public function getActiveOrder(Request $request)
    {
        $data = array();
        $data = $this->order_repo->getActiveOrder($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->userDetails))?
                                                        [
                                                            'id'=>$response->userDetails->id,
                                                            'user_name'=>$response->userDetails->user_name,
                                                            'profile_image'=>$response->userDetails->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }
  
    public function changeOrderStatus(OrderStatusRequest $request)
    {
        $data = array();
        $update = [
                    'status'=> $request->status,
                    'cancel_reason'=> !empty($request->cancel_reason) && $request->status == '2' ? $request->cancel_reason : NULL,
                    'cancel_date'=> !empty($request->cancel_date) && $request->status == '2' ? $request->cancel_date : NULL,
                    'cancel_user_id'=> !empty($request->cancel_date) && $request->status == '2' ? $request->user()->id : NULL,
                  ];

        try{
            DB::beginTransaction();
            $this->order_repo->dataCrud($update, $request->id);
            $data = $this->order_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->user_id : $data->client_id,
                                        'title' => 'Order',
                                        'message' => 'Order is '. $data->status_name,
                                        'parameter' => json_encode(['order_id'=> $data->id]),
                                        'msg_type' => '5',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            return self::sendSuccess($data, 'Order status change');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function addOrderPharmacyReview(ReviewRequest $request)
    {
    
        $data = array();
        $update = [
                    'user_rating'=> $request->rating,
                    'user_review'=> isset($request->comment) ? $request->comment :'',
                  ];

        try{
            DB::beginTransaction();
            $this->order_repo->dataCrud($update, $request->id);
            $data = $this->order_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->user_id : $data->client_id,
                                        'title' => 'Order',
                                        'message' => 'Order review add',
                                        'parameter' => json_encode(['order_id'=> $data->id]),
                                        'msg_type' => '5',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            return self::sendSuccess($data, 'Order Add Review');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
        
    }

    
    public function saveCartCheckout(CartCheckoutRequest $request)
    { 
        
        $cart_details = $this->shop_cart_repo->getUserCart($request->user()->id);
        $pharmacy_user = $this->user_repo->getById($request->user_id);
        if(empty($cart_details) || count($cart_details) == '0'){
            return self::sendError([], 'Cart is Empty');
        }
      
        if(!empty($cart_details)){
            foreach ($cart_details as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                if(empty($stock_available)){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }
        try{
            DB::beginTransaction();
            $order_data = [
                            'user_id'=> $request->user_id,
                            'client_id'=> $request->user()->id,
                            'user_location_id' => !empty($request->user_location_id) ? $request->user_location_id : NULL,
                            'voucher_code_id' => !empty($request->voucher_code_id) ? $request->voucher_code_id : NULL,
                            'delivery_type'=> $request->delivery_type
                        ];
                        
            $order = $this->order_repo->dataCrud($order_data); 
            $transaction_amount = 0;
            $shipping_price = 0;
            $voucher_amount_apply = 0;
            if($request->delivery_type == '0'){
                $shipping_price = $pharmacy_user->userDetails->delivery_charge;
            }
            if(!empty($cart_details) && !empty($order)){
                foreach ($cart_details as $key => $value) {
                    $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                    if(!empty($stock_available)){                    
                        $product_data = [
                                        'capsual_quantity' => $stock_available->capsual_quantity - $value->quantity
                                        ];
                        $this->shop_medicine_repo->dataCrud($product_data, $stock_available->id); 
                    }

                    $order_product_data = [
                                            'order_id'=> $order->id,
                                            'shop_medicine_detail_id' => $value->shop_medicine_detail_id,
                                            'quantity' => $value->quantity,
                                            'medicine_price' => $stock_available->offer_price,
                                        ];
                    $this->order_product_repo->dataCrud($order_product_data); 
                       
                    $transaction_amount += $stock_available->offer_price * $value->quantity;                
                }
                if(!empty($request->voucher_code_id)){
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, '2'); 
                    if(!empty($voucher_code) && !empty($voucher_code->id)){
                        
                        if(!empty($voucher_code->percentage)){
                            $voucher_amount_apply = (($transaction_amount * 100 ) /$voucher_code->percentage);
                        }else{
                            $voucher_amount_apply = $transaction_amount;
                        }

                        if($voucher_amount_apply <= $voucher_code->min_amount){
                            $voucher_amount_apply = $voucher_code->min_amount;
                        }else if($voucher_amount_apply >= $voucher_code->fix_amount){
                            $voucher_amount_apply = $voucher_code->fix_amount;
                        }

                        $this->voucher_code_repo->dataCrud(['quantity' => ($voucher_code->quantity - 1)], $request->voucher_code_id);    
                    }

                }
            $transaction_amount = $transaction_amount - $voucher_amount_apply;
            $order_update = [
                            'total_price' => $transaction_amount,
                            'shipping_price' => $shipping_price,
                            'voucher_amount' => $voucher_amount_apply,
                        ];
                        
                $this->order_repo->dataCrud($order_update, $order->id); 
                $this->shop_cart_repo->clearUserCart($request->user()->id); 
                $data = $this->order_repo->getbyEditId($order->id); 
                if (!empty($data)) {
                    $send_notification = [
                                            'sender_id' => $request->user()->id,
                                            'receiver_id' => ($request->user()->id == $data->user_id) ? $data->user_id : $data->client_id,
                                            'title' => 'Order',
                                            'message' => 'Order Placed',
                                            'parameter' => json_encode(['order_id'=> $data->id]),
                                            'msg_type' => '4',
                                        ];
                    $this->notification_repo->sendingNotification($send_notification);
                }
            }
            DB::commit();
            return self::sendSuccess($data, 'Order Completed');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
}
