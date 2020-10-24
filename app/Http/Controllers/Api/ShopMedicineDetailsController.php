<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ShopMedicalDetailsRequest;

class ShopMedicineDetailsController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineCategories(Request $request){
        $data = array();
        try{
            $data = $this->medicine_category_repo->getbyColumnWithValue('status','0');
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineSubcategories($cate_id){
        $data = array();
        try{
            $column_data = [['medicine_category_id', '=', $cate_id], ['status', '=', '0']];
            $data = $this->medicine_subcategory_repo->getbyMultipleColumnWithValue($column_data);
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineDetails($sub_id){
        $data = array();
        try{
            $column_data = [['medicine_subcategoy_id', '=', $sub_id], ['status', '=', '0']];
            $data = $this->medicine_details_repo->getbyMultipleColumnWithValue($column_data);
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShopProductInfo($id){
        $data = array();
        try{
            $data = $this->shop_medicine_repo->getbyIdedit($id);
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }
 
    public function addShopProduct(ShopMedicalDetailsRequest $request){
        $data = array();
        $check_product = [["user_id",'=' ,$request->user()->id], ['medicine_detail_id','=', $request->medicine_detail_id]];
        $shop_product = $this->shop_medicine_repo->getbyMultipleColumnWithFirstValue($check_product);
        if(!empty($shop_product)){
             return self::sendError([],'Product already registerd please Check');
        }
        $insert_data = [
                        "user_id" => $request->user()->id,
                        "medicine_category_id" => $request->medicine_category_id,
                        "medicine_subcategoy_id" => $request->medicine_subcategoy_id,
                        "medicine_detail_id" => $request->medicine_detail_id,
                        "capsual_quantity" => $request->capsual_quantity,
                        "shirap_ml" => $request->shirap_ml,
                        "mrp_price" => $request->mrp_price,
                        "offer_price" => $request->offer_price,
                        "medicine_type" => $request->medicine_type,
                        "status" => $request->status,
                    ];
            
        try{
            $data = $this->shop_medicine_repo->dataCrud($insert_data);
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
        
    }
   
    public function getShopProduct(Request $request){
        $data = array();            
        try{
            $data = $this->shop_medicine_repo->getShopMedicineProducts($request);
            return self::sendSuccess($data);
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
        
    }
}
