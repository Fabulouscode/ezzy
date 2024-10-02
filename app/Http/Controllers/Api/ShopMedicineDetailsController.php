<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\UserReviewRepository;
use App\Repositories\FavoriteMedicineRepository;
use App\Http\Requests\Api\ShopProductAddRequest;
use App\Http\Requests\Api\ShopProductEditRequest;
use App\Http\Requests\Api\ShopProductDeleteRequest;
use App\Http\Requests\Api\FavoriteRequest;
use App\Http\Requests\Api\ImportMedicineDetailsRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\MedicineDetaisExport;
use App\Imports\ApiMedicineDetaisImport;
use Excel;


class ShopMedicineDetailsController extends BaseApiController
{

    private $medicine_category_repo, $medicine_subcategory_repo, $medicine_details_repo, $shop_medicine_repo, $user_review_repo, $favorite_medicine_repo;

    public function __construct(
        MedicineCategoryRepository $medicine_category_repo,        
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineDetailsRepository $medicine_details_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        UserReviewRepository $user_review_repo,
        FavoriteMedicineRepository $favorite_medicine_repo
        )
    {
        parent::__construct();
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->medicine_details_repo = $medicine_details_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->user_review_repo = $user_review_repo;
        $this->favorite_medicine_repo = $favorite_medicine_repo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineCategories(Request $request)
    {
        $data = array();
        $data = $this->medicine_category_repo->getbyColumnWithValue('status','0')->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'name'=>$response->name,
                                        'description'=>$response->description,
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
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
        // $column_data = [['medicine_category_id', '=', $cate_id], ['status', '=', '0']];
        // $data = $this->medicine_subcategory_repo->getbyMultipleColumnWithValue($column_data)->map(function ($response){
        //                             return [
        //                                 'id'=>$response->id,
        //                                 'medicine_category_id'=>$response->medicine_category_id,
        //                                 'name'=>$response->name,
        //                                 'description'=>$response->description,
        //                                 'status'=>$response->status,
        //                                 'status_name'=>$response->status_name,
        //                             ];
        //                         });
        return self::sendSuccess($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineDetails($cate_id)
    {
        $data = array();
        $column_data = [['medicine_category_id', '=', $cate_id], ['status', '=', '0']];
        $data = $this->medicine_details_repo->getbyMultipleColumnWithValue($column_data)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'medicine_name'=>$response->medicine_name,
                                        'size_dosage'=>$response->size_dosage,         
                                        'medicine_sku'=>$response->medicine_sku,
                                        'medicine_type'=> $response->medicine_type,
                                        'medicine_type_name'=> $response->medicine_type_name,
                                        'shirap_ml'=> $response->size_dosage,
                                        'mrp_price'=> $response->mrp_price,
                                        'quantity'=> $response->quantity,
                                    ];
                                });
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
        if(!empty($data)){
             $data = $data->format();
        }
        return self::sendSuccess($data);
    }
 
    public function addShopProduct(ShopProductAddRequest $request)
    {
        $data = array();
        if(empty($request->id)){
            $check_product = [["user_id",'=' ,$request->user()->id], ['medicine_detail_id','=', $request->medicine_detail_id], ['status','0']];
            $shop_product = $this->shop_medicine_repo->getbyMultipleColumnWithFirstValue($check_product);
            if(!empty($shop_product)){
                return self::sendError([],'Product is already registered. Please check.');
            }
        }


        $insert_data = [
                        "user_id" => $request->user()->id,
                        "medicine_category_id" => $request->medicine_category_id,
                        "medicine_detail_id" => $request->medicine_detail_id,
                        "capsual_quantity" => $request->capsual_quantity,
                        "mrp_price" => $request->mrp_price,
                        "offer_price" => !empty($request->offer_price) ? $request->offer_price : $request->mrp_price,
                        "status" => '0'
                    ];
            
        try{
            DB::beginTransaction();
            
            if(empty($request->id)){
                $check_product = [["user_id",'=' ,$request->user()->id], ['medicine_detail_id','=', $request->medicine_detail_id], ['status','1']];
                $inactive_product = $this->shop_medicine_repo->getbyMultipleColumnWithFirstValue($check_product);
            }

            if(!empty($inactive_product)){
                 $data = $this->shop_medicine_repo->dataCrud($insert_data, $inactive_product->id);
            }else{
                if(!empty($request->id)){
                    $data = $this->shop_medicine_repo->dataCrud($insert_data, $request->id);
                }else{
                    $data = $this->shop_medicine_repo->dataCrud($insert_data);
                }
            }

            DB::commit();
            return self::sendSuccess($data);
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
        
    }
   
    public function editShopProduct(ShopProductEditRequest $request)
    {
        $data = array();
        $insert_data = [
                        "capsual_quantity" => $request->capsual_quantity,
                        "mrp_price" => $request->mrp_price,
                        "offer_price" => !empty($request->offer_price) ? $request->offer_price : $request->mrp_price,
                    ];
            
        try{
            DB::beginTransaction();            
            $data = $this->shop_medicine_repo->dataCrud($insert_data, $request->id);
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
        $data = $this->shop_medicine_repo->getShopMedicineProducts($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'mrp_price'=>$response->mrp_price,
                                        'offer_price'=>$response->offer_price,
                                        'capsual_quantity'=>$response->capsual_quantity,      
                                        'medicine_type'=> isset($response->medicineDetails) ? $response->medicineDetails->medicine_type : '',
                                        'medicine_type_name'=> isset($response->medicineDetails) ? $response->medicineDetails->medicine_type_name : '',
                                        'shirap_ml'=> isset($response->medicineDetails) ? $response->medicineDetails->size_dosage : '',
                                        'medicine_details'=>(isset($response->medicineDetails))?
                                                        [
                                                            'id'=>$response->medicineDetails->id,
                                                            'medicine_image'=>$response->medicineDetails->medicine_image,
                                                            'medicine_name'=>$response->medicineDetails->medicine_name,
                                                            'medicine_sku'=>$response->medicineDetails->medicine_sku,
                                                            'size_dosage'=>$response->medicineDetails->size_dosage,                
                                                        ]:'',
                                        'favorite_product'=>(isset($response->favoriteProduct))? 1 : 0,
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        
        return self::sendSuccess($data);
    }
  
    public function deleteShopProduct(ShopProductDeleteRequest $request)
    {
        $data = array();         
        if(!empty($request->ids)){
            try{
                DB::beginTransaction();
                $data = $this->shop_medicine_repo->destroyMultiple($request->ids);
                DB::commit();
                return self::sendSuccess($data);
            }catch(\Exception $e){
                DB::rollBack();
                return self::sendException($e);
            }
        }
        return self::sendError('','Shop Product Not Select');
    }


    public function getMedicineDetailsWithSearch(Request $request)
    {
        $data = array();         
        $data = $this->medicine_details_repo->getMedicineDetailsWithSearch($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'medicine_name'=>$response->medicine_name
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getShopProductWithSearch(Request $request)
    {
        $data = array();         
        $data = $this->shop_medicine_repo->getShopProductWithSearch($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'medicine_name'=>!empty($response->medicineDetails) ? $response->medicineDetails->medicine_name : ''
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getFavoriteMedicine(Request $request)
    {
        $data = $this->favorite_medicine_repo->getFavoriteMedicine($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'shop_medicine_detail_id'=>$response->shopMedicineDetails->id,
                                        'shop_id'=>$response->shopMedicineDetails->user_id,
                                        'mrp_price'=>$response->shopMedicineDetails->mrp_price,
                                        'offer_price'=>$response->shopMedicineDetails->offer_price,
                                        'capsual_quantity'=>$response->shopMedicineDetails->capsual_quantity,                                        
                                        'medicine_type'=> isset($response->shopMedicineDetails->medicineDetails) ? $response->shopMedicineDetails->medicineDetails->medicine_type : '',
                                        'medicine_type_name'=> isset($response->shopMedicineDetails->medicineDetails) ? $response->shopMedicineDetails->medicineDetails->medicine_type_name : '',
                                        'shirap_ml'=> isset($response->shopMedicineDetails->medicineDetails) ? $response->shopMedicineDetails->medicineDetails->size_dosage : '',
                                        'medicine_details'=>(isset($response->shopMedicineDetails->medicineDetails))?
                                                        [
                                                            'id'=>$response->shopMedicineDetails->medicineDetails->id,
                                                            'medicine_image'=>$response->shopMedicineDetails->medicineDetails->medicine_image,
                                                            'medicine_name'=>$response->shopMedicineDetails->medicineDetails->medicine_name,
                                                            'medicine_sku'=>$response->shopMedicineDetails->medicineDetails->medicine_sku,
                                                            'size_dosage'=>$response->shopMedicineDetails->medicineDetails->size_dosage,         
                                                        ]:'',
                                        'favorite_product'=> 1,
                                        'status'=>$response->shopMedicineDetails->status,
                                        'status_name'=>$response->shopMedicineDetails->status_name,
                                    ];
                                });; 
        return self::sendSuccess($data, 'Favorite medicine get');
    }

    public function addFavoriteMedicine(FavoriteRequest $request)
    {
        if(!empty($request->user()->id) && !empty($request->shop_medicine_detail_id)){
             //Favorite medicine already check
            $favorite_medicine = $this->favorite_medicine_repo->checkFavoriteMedicine($request);
            if(!empty($favorite_medicine)){
                return self::sendError([], 'Favorite medicine already added.');
            }
        }
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

    public function removeFavoriteMedicine(FavoriteRequest $request)
    {
        $data = $this->favorite_medicine_repo->removeFavoriteMedicine($request); 
        return self::sendSuccess('', 'Favorite medicine remove');
    }

    /**
     * medicine details export.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMedicineDetails(Request $request)
    {
        Excel::store(new MedicineDetaisExport, 'public/csv/medicine_details.csv');
        $data['file'] = 'public/csv/medicine_details.csv';
        $data['url'] = url('storage/csv/medicine_details.csv');
        return self::sendSuccess($data, 'file export Successfully');
    }
  
    /**
     * medicine details export.
     *
     * @return \Illuminate\Http\Response
     */
    public function importMedicineDetails(ImportMedicineDetailsRequest $request)
    {
        if(!empty($request->file('medicine_file'))) {          
            $file = $request->file('medicine_file');
            Excel::queueImport(new ApiMedicineDetaisImport($request->user()->id), $file);
        }
        return self::sendSuccess('', 'file export Successfully');
    }

}
