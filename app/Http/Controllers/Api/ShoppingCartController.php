<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ShoppingCartRequest;
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\FavoriteRequest;

class ShoppingCartController extends BaseApiController
{
    public function addToCart(ShoppingCartRequest $request){
        $cart_check = $this->shop_cart_repo->checkCart($request->user()->id, $request->shop_medicine_detail_id);
        if(!empty($cart_check) && !empty($cart_check->id)){
                $update_data = [
                                'quantity'=> $cart_check->quantity + $request->quantity,
                                ];
           try{
                $this->shop_cart_repo->dataCrud($update_data, $cart_check->id);
                $data = $this->shop_cart_repo->getById($cart_check->id);
                return self::sendSuccess($data, 'Cart update Success');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }

        $add_data = [
                        'user_id' => $request->user()->id,
                        'shop_medicine_detail_id' => $request->shop_medicine_detail_id,
                        'quantity'=> $request->quantity,
                    ];
        try{
            $data = $this->shop_cart_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Cart add Success');
        }catch(\Exception $e){
                return self::sendException($e);
        }
    }

    public function getUserCart(Request $request){
        $data = $this->shop_cart_repo->getUserCart($request->user()->id);
        return self::sendSuccess($data , 'get Cart data');
    }

    public function getToCart($id){
        $data = $this->shop_cart_repo->getById($id);
        return self::sendSuccess($data , 'get Cart data');
    }

    public function updateToCartAddition($id){
        $cart_check = $this->shop_cart_repo->getById($id);
        $update_data = [
                        'quantity'=> $cart_check->quantity + 1,
                        ];

        try{
            $this->shop_cart_repo->dataCrud($update_data, $id);
            $data = $this->shop_cart_repo->getById($id);
            return self::sendSuccess($data, 'Cart add Success');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
   
    public function updateToCartSubtraction($id){
        $cart_check = $this->shop_cart_repo->getById($id);
        if(!empty($cart_check->quantity) && $cart_check->quantity > 0){
            $update_data = [
                            'quantity'=> $cart_check->quantity - 1,
                           ];
           try{
                $this->shop_cart_repo->dataCrud($update_data, $id);
                $data = $this->shop_cart_repo->getById($id);
                return self::sendSuccess($data, 'Cart add Success');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            $this->shop_cart_repo->destroy($id); 
            return self::sendSuccess('', 'Cart remove Success');
        }

    }

    public function removeToCart($id){
        $data = $this->shop_cart_repo->getById($id);
        if(!empty($data)){
            $this->shop_cart_repo->destroy($id); 
            return self::sendSuccess('', 'Cart remove Success');
        }
        
        return self::sendError('Data Not found');
    }

    public function clearUserCart(Request $request){
        $this->shop_cart_repo->clearUserCart($request->user()->id); 
        return self::sendSuccess('', 'Cart clear Success');
    }

    public function clearShopCart(Request $request, $shop_id){
        if(!empty($shop_id)){
            $this->shop_cart_repo->clearShopCart($request->user()->id, $shop_id); 
            return self::sendSuccess('', 'Cart clear Success');
        }
      
        return self::sendError('Data Not found');
    }
    

    public function saveCartCheckout(CartCheckoutRequest $request){ 

        if(!empty($request->order_prodcuts)){
            foreach ($request->order_prodcuts as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMeditionStock($value); 
                if(empty($stock_available)){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }
        try{
            $order_data = [
                            'user_id'=> $request->user_id,
                            'client_id'=> $request->user()->id,
                            'user_location_id' => !empty($request->user_location_id) ? $request->user_location_id : NULL,
                            'total_price' => $request->total_price,
                            'shipping_price' => $request->shipping_price,
                            'delivery_type'=> $request->delivery_type
                        ];
                        
            $order = $this->order_repo->dataCrud($order_data); 

            if(!empty($request->order_prodcuts) && !empty($order)){
                foreach ($request->order_prodcuts as $key => $value) {
                    $order_product_data = [
                                            'order_id'=> $order->id,
                                            'shop_medicine_detail_id' => $value['shop_medicine_detail_id'],
                                            'quantity' => $value['quantity']
                                        ];
                    $this->order_product_repo->dataCrud($order_product_data); 
                    
                    $stock_available = $this->shop_medicine_repo->checkMeditionStock($value); 
                    if(!empty($stock_available)){                    
                        $product_data = [
                                        'capsual_quantity' => $stock_available->capsual_quantity - $value['quantity']
                                        ];
                        $this->shop_medicine_repo->dataCrud($product_data, $stock_available->id); 
                    }
                    
                }

            }
            return self::sendSuccess('', 'Cart checkout Success');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }


    public function getFavoriteMedicine(Request $request){
        $data = $this->favorite_medicine_repo->getFavoriteMedicine($request); 
        return self::sendSuccess($data, 'Favorite medicine get');
    }

    public function addFavoriteMedicine(FavoriteRequest $request){
        $add_data =[
                        'user_id' => $request->user()->id,
                        'shop_medicine_detail_id'=>$request->shop_medicine_detail_id
                    ];
        try{
            $data = $this->favorite_medicine_repo->dataCrud($add_data); 
            return self::sendSuccess($data, 'Favorite medicine add');
        }catch(\Exception $e){
            return self::sendException($e);
        }

    }

    public function removeFavoriteMedicine(FavoriteRequest $request){
        $data = $this->favorite_medicine_repo->removeFavoriteMedicine($request); 
        return self::sendSuccess('', 'Favorite medicine remove');
    }



}
