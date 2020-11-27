<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\UserReviewRepository;
use App\Http\Requests\Api\ShopMedicalDetailsRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShopMedicineDetailsController extends BaseApiController
{

    private $medicine_category_repo, $medicine_subcategory_repo, $medicine_details_repo, $shop_medicine_repo, $user_review_repo;

    public function __construct(
        MedicineCategoryRepository $medicine_category_repo,        
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineDetailsRepository $medicine_details_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        UserReviewRepository $user_review_repo
        )
    {
        parent::__construct();
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->medicine_details_repo = $medicine_details_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->user_review_repo = $user_review_repo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineCategories(Request $request)
    {
        $data = array();
        $data = $this->medicine_category_repo->getbyColumnWithValue('status','0');
        return self::sendSuccess($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineSubcategories($cate_id)
    {
        $data = array();
        $column_data = [['medicine_category_id', '=', $cate_id], ['status', '=', '0']];
        $data = $this->medicine_subcategory_repo->getbyMultipleColumnWithValue($column_data);
        return self::sendSuccess($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineDetails($sub_id)
    {
        $data = array();
        $column_data = [['medicine_subcategoy_id', '=', $sub_id], ['status', '=', '0']];
        $data = $this->medicine_details_repo->getbyMultipleColumnWithValue($column_data);
        return self::sendSuccess($data);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShopProductInfo($id)
    {
        $data = array();
        $data = $this->shop_medicine_repo->getbyIdedit($id);
        return self::sendSuccess($data);
    }
 
    public function addShopProduct(ShopMedicalDetailsRequest $request)
    {
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
            DB::beginTransaction();
            $data = $this->shop_medicine_repo->dataCrud($insert_data);
            DB::commit();
            return self::sendSuccess($data);
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
        
    }
   
    public function getShopProduct(Request $request)
    {
        $data = array();            
        $data = $this->shop_medicine_repo->getShopMedicineProducts($request);
        return self::sendSuccess($data);
    }


}
