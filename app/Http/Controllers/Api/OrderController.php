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
use App\Http\Requests\Api\OrderTrackingStatusRequest;
use App\Http\Requests\Api\OrderVerifySMSRequest;
use App\Repositories\UserTransactionRepository;
use App\Http\Requests\Api\ReviewRequest;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends BaseApiController
{
    private $order_repo, $user_repo, $user_transaction_repo, $voucher_code_repo, $shop_medicine_repo, $order_tracking_repo, $shop_cart_repo, $order_product_repo, $notification_repo;

    public function __construct(
        ShoppingCartRepository $shop_cart_repo,        
        UserTransactionRepository $user_transaction_repo, 
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
        $this->user_transaction_repo = $user_transaction_repo;
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
                                                            'profile_image'=>$response->userDetails->profile_image,
                                                            'latitude'=>$response->userDetails->latitude,
                                                            'longitude'=>$response->userDetails->longitude,
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


    public function getPendingOrder(Request $request)
    {
        $data = array();
        $data = $this->order_repo->getPendingOrder($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'delivery_type'=>$response->delivery_type,
                                        'delivery_type_name'=>$response->delivery_type_name,                                        
                                        'user_location'=>(!empty($this->userLocationDetails)) ? $this->userLocationDetails : NULL,
                                        'delivery_location'=> !empty($response->userLocationDetails) && !empty($response->userLocationDetails->address) && $response->delivery_type == '0' ? $response->userLocationDetails->address : NULL,
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image,
                                                        ]:'',
                                        'user'=>(isset($response->userDetails))?
                                                        [
                                                            'id'=>$response->userDetails->id,
                                                            'user_name'=>$response->userDetails->user_name,
                                                            'profile_image'=>$response->userDetails->profile_image,
                                                            'latitude'=>$response->userDetails->latitude,
                                                            'longitude'=>$response->userDetails->longitude,
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
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
                                                            'profile_image'=>$response->userDetails->profile_image,
                                                            'latitude'=>$response->userDetails->latitude,
                                                            'longitude'=>$response->userDetails->longitude,
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
                                                            'profile_image'=>$response->userDetails->profile_image,
                                                            'latitude'=>$response->userDetails->latitude,
                                                            'longitude'=>$response->userDetails->longitude,
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
                                        'completed_datetime'=>$response->completed_datetime,
                                        'created_at'=>$response->created_at,
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
                                                            'profile_image'=>$response->userDetails->profile_image,
                                                            'latitude'=>$response->userDetails->latitude,
                                                            'longitude'=>$response->userDetails->longitude,
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
            
            if($request->status == '4' && $data->status == '2'){
                return self::sendError('', 'Order is dispatched you can not cancel.');
            }

            if($request->status == '0'){
                $notification_message = 'Order booked by '. $request->user()->user_name;
            }else if($request->status == '1'){
                $notification_message = 'Order accepted by '. $request->user()->user_name;
            }else if($request->status == '2'){
                $notification_message = 'Order dispatched by '. $request->user()->user_name;
            }else if($request->status == '3'){
                $notification_message = 'Order received by '. $request->user()->user_name;
            }else if($request->status == '4'){
                $notification_message = 'Order canceled by '. $request->user()->user_name;
            }else{
                $notification_message = 'Order '.strtolower($data->status_name).' by '. $request->user()->user_name;
            } 

            if($request->status == '4' && $data->status != '2'){
                if(!empty($data) && !empty($data->orderProductDetails)){
                    foreach ($data->orderProductDetails as $key => $value) {
                        $stock_available = $this->shop_medicine_repo->addMedicineStockCancel($value);
                        if (!empty($stock_available)) {
                            $product_data = [
                                            'capsual_quantity' => $stock_available->capsual_quantity + $value->quantity
                                            ];
                            $this->shop_medicine_repo->dataCrud($product_data, $stock_available->id);
                        }
                    }
                }
                $order_tracking = [
                    'order_id'=> $request->id,
                    'title'=> 'Order Cancelled',
                    'description'=> 'Order Cancelled',
                    'status'=> '4',
                    'estimation_datetime'=> $this->order_tracking_repo->getCurrentDateTime(),
                  ];
                $this->order_tracking_repo->dataCrud($order_tracking);
              
                    $update_transaction = [
                        'payout_amount'=> '0',
                        'fees_charge'=> '0',
                    ];
                    $this->user_transaction_repo->dataCrud($update_transaction,$data->transaction_id);

                    $add_transaction = [
                        'user_id'=> $data->client_id,
                        'transaction_date'=> $this->user_repo->getCurrentDateTime(),
                        'amount'=> $data->total_price,                        
                        'mode_of_payment'=> '0',
                        'transaction_type'=> '0',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                        'order_id'=> $request->id,
                        'transaction_msg'=>'Order amount refund',
                    ];
                    $this->user_transaction_repo->dataCrud($add_transaction);
                    $this->user_repo->userWalletUpdate($data->client_id);   

            }else if($request->status == '3'){
                $order_tracking = [
                    'order_id'=> $request->id,
                    'title'=> 'Order Completed',
                    'description'=> 'Order Completed',
                    'status'=> '5',
                    'estimation_datetime'=>  $this->order_tracking_repo->getCurrentDateTime(),
                  ];
                $this->order_tracking_repo->dataCrud($order_tracking);

                $order_update = ['status' => $request->status,'completed_datetime' => $this->user_repo->getCurrentDateTime()];
                $this->order_repo->dataCrud($order_update, $request->id);

            }else if($request->status == '1'){
                $order_tracking = [
                    'order_id'=> $request->id,
                    'title'=> 'Order Accepted',
                    'description'=> 'Order Accepted',
                    'status'=> '1',
                    'estimation_datetime'=>  $this->order_tracking_repo->getCurrentDateTime(),
                  ];
                $this->order_tracking_repo->dataCrud($order_tracking);
            }else if($request->status == '2'){
                $order_tracking = [
                    'order_id'=> $request->id,
                    'title'=> 'Order On the Way',
                    'description'=> 'Order On the Way',
                    'status'=> '2',
                    'estimation_datetime'=>  $this->order_tracking_repo->getCurrentDateTime(),
                  ];
                $this->order_tracking_repo->dataCrud($order_tracking);
            }

            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Order',
                                        'message' => $notification_message,
                                        'parameter' => json_encode(['order_id'=> $data->id]),
                                        'msg_type' => '5',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();            
            $data = $this->order_repo->getById($request->id);
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'Order status change');
            }
            return self::sendSuccess($data, 'Order status change');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
   
    public function changeOrderTrackingStatus(OrderTrackingStatusRequest $request)
    {
        $data = array();
        $order_tracking = [
                    'order_id'=> $request->order_id,
                    // 'title'=> $request->title,
                    // 'description'=> $request->description,
                    'status'=> $request->status,
                    'estimation_datetime'=> $this->order_tracking_repo->getCurrentDateTime(),
                  ];

        try{
            DB::beginTransaction();
            $this->order_tracking_repo->dataCrud($order_tracking);
            $data = $this->order_repo->getById($request->order_id);
            if($request->status == '0'){
                $notification_message = 'Order Placed';
            }else if($request->status == '1'){
                $notification_message = 'Order Accepted';
            }else if($request->status == '2'){
                $notification_message = 'Order On the Way';
            }else if($request->status == '3'){
                $notification_message = 'Order Delivered';
            }else if($request->status == '4'){
                $notification_message = 'Order Cancel';
            }else{
                $notification_message = 'Order Completed';
            } 

            // if($request->status == '2'){
            //     $order_otp_code = $this->order_repo->generateOTPCode();
            //     $order_update = ['otp_code' => $order_otp_code];
            //     $message = 'The OTP is '.$order_otp_code.' to Verify Order Completed.';
            //     // $sent_msg = $this->order_repo->sendMessage($message, $data->clientDetails->country_code.$data->clientDetails->mobile_no);
            //     // if(!empty($sent_msg)){
            //     //      return self::sendError('', 'SMS Sending Failed');
            //     // }
            //     $this->order_repo->dataCrud($order_update, $request->order_id);
            // }
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Order Tracking',
                                        'message' => $notification_message,
                                        'parameter' => json_encode(['order_id'=> $data->id]),
                                        'msg_type' => '5',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            $data = $this->order_repo->getById($request->order_id);
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'order tracking status change');
            }
            DB::commit();
            return self::sendSuccess($data, 'Order tracking status change');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function resendSMS($order_id)
    {
        try{
            $data = $this->order_repo->getById($order_id);
            $order_otp_code = $this->order_repo->generateOTPCode();
            $order_update = ['otp_code' => $order_otp_code];
            $message = 'The OTP is '.$order_otp_code.' to Verify Order Completed.';
            // $sent_msg = $this->order_repo->sendMessage($message, $data->clientDetails->country_code.$data->clientDetails->mobile_no);
            // if(!empty($sent_msg)){
            //    return self::sendError('', 'SMS Sending Failed');
            // }
            $this->order_repo->dataCrud($order_update, $order_id);
            return self::sendSuccess([
                'data' => $data,
            ]);
    
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function verifyOTP(OrderVerifySMSRequest $request)
    {
        $data = $this->order_repo->getById($request->order_id);  
        if(!empty($data) && $data->otp_code == $request->otp_code){   
            try{
                $order_tracking = [
                    'order_id'=> $request->order_id,
                    'title'=> 'Order Completed',
                    'description'=> 'Order Completed',
                    'status'=> '4',
                    'estimation_datetime'=>  $request->completed_datetime,
                ];
                $this->order_tracking_repo->dataCrud($order_tracking);
                $order_update = ['status' => 1,'completed_datetime' => $request->completed_datetime];
                $this->order_repo->dataCrud($order_update, $request->order_id);
                return self::sendSuccess([],'Mobile no verify');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'Verify OTP code is wrong please check');
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
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Order',
                                        'message' => 'Order review added by '.$request->user()->user_name,
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
                            'delivery_type'=> $request->delivery_type,
                            'status'=> '5'
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
                    $order_product_data = [
                                            'order_id'=> $order->id,
                                            'shop_medicine_detail_id' => $value->shop_medicine_detail_id,
                                            'quantity' => $value->quantity,
                                            'medicine_price' => !empty($stock_available->offer_price) ? $stock_available->offer_price : $stock_available->mrp_price,
                                        ];
                    $this->order_product_repo->dataCrud($order_product_data); 
                    $stock_available_offer_price = 0;
                    $stock_available_offer_price = !empty($stock_available->offer_price) ? $stock_available->offer_price : $stock_available->mrp_price;
                    $transaction_amount += $stock_available_offer_price * $value->quantity;                
                }
                if(!empty($request->voucher_code_id)){
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, '2'); 
                    if(!empty($voucher_code) && !empty($voucher_code->id)){
                        
                        if(!empty($voucher_code->percentage)){
                            $voucher_amount_apply = (($transaction_amount / 100 ) * $voucher_code->percentage);
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
            if(!empty($order) && $request->delivery_type == '0'){
                    $transaction_amount += $shipping_price;
            }
            $order_update = [
                            'total_price' => $transaction_amount,
                            'shipping_price' => $shipping_price,
                            'voucher_amount' => $voucher_amount_apply,
                        ];
                        
                $this->order_repo->dataCrud($order_update, $order->id); 
                $this->shop_cart_repo->clearUserCart($request->user()->id); 
                $data = $this->order_repo->getbyEditId($order->id); 
                // if (!empty($data)) {
                //     $send_notification = [
                //                             'sender_id' => $request->user()->id,
                //                             'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                //                             'title' => 'Order',
                //                             'message' => 'Order placed by '. $request->user()->user_name,
                //                             'parameter' => json_encode(['order_id'=> $data->id]),
                //                             'msg_type' => '4',
                //                         ];
                //     $this->notification_repo->sendingNotification($send_notification);
                // }
            }
            DB::commit();
            return self::sendSuccess($data, 'Order Completed');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function saveReorder(Request $request, $order_id)
    {
        if(empty($order_id)){
            return self::sendError('', 'Order is not available');
        }

        $data = $this->order_repo->getById($order_id);

        $this->shop_cart_repo->clearUserCart($request->user()->id); 
        
        if(!empty($data->orderProductDetails) && count($data->orderProductDetails) > 0){
            foreach ($data->orderProductDetails as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                if(empty($stock_available)){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }
        
        try{
            DB::beginTransaction();
            if(!empty($data->orderProductDetails) && count($data->orderProductDetails) > 0){
                foreach ($data->orderProductDetails as $key => $value) {
                    $add_data = [
                        'user_id' => $request->user()->id,
                        'shop_medicine_detail_id' => $value->shop_medicine_detail_id,
                        'quantity'=> $value->quantity,
                    ];
                    $data = $this->shop_cart_repo->dataCrud($add_data);
                }
            }           
            DB::commit();
            return self::sendSuccess([], 'Cart add Success');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
}
