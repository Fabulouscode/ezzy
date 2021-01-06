<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\ShoppingCartRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\FavoriteMedicineRepository;
use App\Http\Requests\Api\ShoppingCartRequest;
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\FavoriteRequest;
use Illuminate\Support\Facades\DB;

class ShoppingCartController extends BaseApiController
{

    private $shop_cart_repo, $shop_medicine_repo, $order_repo, $order_product_repo, $favorite_medicine_repo;

    public function __construct(
        ShoppingCartRepository $shop_cart_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo,
        FavoriteMedicineRepository $favorite_medicine_repo
        )
    {
        parent::__construct();
        $this->shop_cart_repo = $shop_cart_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
        $this->favorite_medicine_repo = $favorite_medicine_repo;
    }


    public function addToCart(ShoppingCartRequest $request)
    {

        $clearCart_other_shop = $this->shop_cart_repo->checkCartInOthershopItem($request->user()->id, $request->shop_id);

        if(empty($clearCart_other_shop)){
            $this->shop_cart_repo->clearUserCart($request->user()->id); 
        }
        
        $cart_check = $this->shop_cart_repo->checkCart($request->user()->id, $request->shop_medicine_detail_id);
        
        $cart_details = $this->shop_cart_repo->getUserCart($request->user()->id);
        if(!empty($cart_details)){
            foreach ($cart_details as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                if(empty($stock_available)){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }

        if(!empty($cart_check) && !empty($cart_check->id)){
        
            $update_data = [
                                'quantity'=> $cart_check->quantity + $request->quantity,
                                ];
           try{
                DB::beginTransaction();
                $this->shop_cart_repo->dataCrud($update_data, $cart_check->id);
                $data = $this->shop_cart_repo->getById($cart_check->id);
                DB::commit();
                return self::sendSuccess($data, 'Cart update Success');
            }catch(\Exception $e){
                DB::rollBack();
                return self::sendException($e);
            }
        }

        $add_data = [
                        'user_id' => $request->user()->id,
                        'shop_medicine_detail_id' => $request->shop_medicine_detail_id,
                        'quantity'=> $request->quantity,
                    ];
        try{
            DB::beginTransaction();
            $data = $this->shop_cart_repo->dataCrud($add_data);
            DB::commit();
            return self::sendSuccess($data, 'Cart add Success');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function getUserCart(Request $request)
    {
        $data = $this->shop_cart_repo->getUserCart($request->user()->id)->map(function ($response){
                                    return [
                                        'id'=>$response->id,                                        
                                        'quantity'=>$response->quantity,
                                        'shop_id'=>$response->shopMedicineDetails->id,
                                        'mrp_price'=>$response->shopMedicineDetails->mrp_price,
                                        'offer_price'=>$response->shopMedicineDetails->offer_price,
                                        'medicine_type'=>$response->shopMedicineDetails->medicine_type,
                                        'medicine_type_name'=>$response->shopMedicineDetails->medicine_type_name,
                                        'capsual_quantity'=>$response->shopMedicineDetails->capsual_quantity,
                                        'shirap_ml'=>$response->shopMedicineDetails->shirap_ml,
                                        'shipping_price'=>(!empty($response->shopMedicineDetails->user) && !empty($response->shopMedicineDetails->user->userDetails)) ? $response->shopMedicineDetails->user->userDetails->delivery_charge : '',
                                        'medicine_details'=>(isset($response->shopMedicineDetails->medicineDetails))?
                                                        [
                                                            'id'=>$response->shopMedicineDetails->medicineDetails->id,
                                                            'medicine_image'=>$response->shopMedicineDetails->medicineDetails->medicine_image,
                                                            'medicine_name'=>$response->shopMedicineDetails->medicineDetails->medicine_name,
                                                            'medicine_sku'=>$response->shopMedicineDetails->medicineDetails->medicine_sku,
                                                        ]:'',
                                        'status'=>$response->shopMedicineDetails->status,
                                        'status_name'=>$response->shopMedicineDetails->status_name,
                                    ];
                                });
        return self::sendSuccess($data , 'get Cart data');
    }

    public function getToCart($id)
    {
        $data = $this->shop_cart_repo->getById($id);
        return self::sendSuccess($data , 'get Cart data');
    }

    public function updateToCartAddition(Request $request, $id)
    {
        $cart_details = $this->shop_cart_repo->getUserCart($request->user()->id);
        if(!empty($cart_details)){
            foreach ($cart_details as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                if(empty($stock_available) && $value->id == $id){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }

        $cart_check = $this->shop_cart_repo->getById($id);
        $update_data = [
                        'quantity'=> $cart_check->quantity + 1,
                        ];

        try{
            DB::beginTransaction();
            $this->shop_cart_repo->dataCrud($update_data, $id);
            $data = self::getUserCartList($request);
            DB::commit();
            return self::sendSuccess($data, 'Cart add Success');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
   
    public function updateToCartSubtraction(Request $request, $id)
    {
       
        $cart_check = $this->shop_cart_repo->getById($id);
        if(!empty($cart_check->quantity) && $cart_check->quantity > 0){
            $update_data = [
                            'quantity'=> $cart_check->quantity - 1,
                           ];
           try{
                $this->shop_cart_repo->dataCrud($update_data, $id);
                $data = self::getUserCartList($request);
                return self::sendSuccess($data, 'Cart add Success');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            $this->shop_cart_repo->destroy($id); 
            return self::sendSuccess('', 'Cart remove Success');
        }

    }

    public function removeToCart($id)
    {
        $data = $this->shop_cart_repo->getById($id);
        if(!empty($data)){
            $this->shop_cart_repo->destroy($id); 
            return self::sendSuccess('', 'Cart remove Success');
        }
        
        return self::sendError('Data Not found');
    }

    public function clearUserCart(Request $request)
    {
        $this->shop_cart_repo->clearUserCart($request->user()->id); 
        return self::sendSuccess('', 'Cart clear Success');
    }

    public function clearShopCart(Request $request, $shop_id)
    {
        if(!empty($shop_id)){
            $this->shop_cart_repo->clearShopCart($request->user()->id, $shop_id); 
            return self::sendSuccess('', 'Cart clear Success');
        }
      
        return self::sendError('Data Not found');
    }
    
    public function getUserCartList(Request $request)
    {
        return $this->shop_cart_repo->getUserCart($request->user()->id)->map(function ($response){
                                    return [
                                        'id'=>$response->id,                                        
                                        'quantity'=>$response->quantity,
                                        'shop_id'=>$response->shopMedicineDetails->id,
                                        'mrp_price'=>$response->shopMedicineDetails->mrp_price,
                                        'offer_price'=>$response->shopMedicineDetails->offer_price,
                                        'medicine_type'=>$response->shopMedicineDetails->medicine_type,
                                        'medicine_type_name'=>$response->shopMedicineDetails->medicine_type_name,
                                        'capsual_quantity'=>$response->shopMedicineDetails->capsual_quantity,
                                        'shirap_ml'=>$response->shopMedicineDetails->shirap_ml,
                                        'shipping_price'=>(!empty($response->shopMedicineDetails->user) && !empty($response->shopMedicineDetails->user->userDetails)) ? $response->shopMedicineDetails->user->userDetails->delivery_charge : '',
                                        'medicine_details'=>(isset($response->shopMedicineDetails->medicineDetails))?
                                                        [
                                                            'id'=>$response->shopMedicineDetails->medicineDetails->id,
                                                            'medicine_image'=>$response->shopMedicineDetails->medicineDetails->medicine_image,
                                                            'medicine_name'=>$response->shopMedicineDetails->medicineDetails->medicine_name,
                                                            'medicine_sku'=>$response->shopMedicineDetails->medicineDetails->medicine_sku,
                                                        ]:'',
                                        'status'=>$response->shopMedicineDetails->status,
                                        'status_name'=>$response->shopMedicineDetails->status_name,
                                    ];
                                });
    }


}
