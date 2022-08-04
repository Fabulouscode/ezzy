<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\MedicalCategoryRepository;
use App\Repositories\MedicalItemRepository;
use App\Repositories\LabReportRepository;
use App\Http\Requests\Api\UserLabReportRequest;

class MedicalController extends BaseApiController
{
     private $medical_cat_repo, $medical_item_repo, $lab_report_repo;

    public function __construct(
        MedicalCategoryRepository $medical_cat_repo,
        MedicalItemRepository $medical_item_repo,
        LabReportRepository $lab_report_repo
        )
    {
        parent::__construct();
        $this->medical_cat_repo = $medical_cat_repo;
        $this->medical_item_repo = $medical_item_repo;
        $this->lab_report_repo = $lab_report_repo;
    }

    public function getMedicalCategory(Request $request)
    {
        $data = array();
        $data = $this->medical_cat_repo->getAll();
        return self::sendSuccess($data, 'medical category List');
    }

    public function getMedicalItemUsingCatID($id)
    {
        $data = array();
        $data = $this->medical_item_repo->getbyMedicalCategoryId($id)->map(function ($response){
                                    return [  
                                        'id'=>$response->id,
                                        'medical_item_name'=>$response->medical_item_name,
                                        'medical_category_id'=>$response->medical_category_id
                                    ];
                                });
        return self::sendSuccess($data, 'medical item List');
    }

    // lab report
    public function getLabReportDetails(Request $request)
    {
        $data = array();
        $data = $this->lab_report_repo->getbyUserIdLabReport($request);
        return self::sendSuccess($data, 'Lab Report details');
    }

    public function addLabReportDetails(UserLabReportRequest $request)
    {
        $data = array();
        $add_data = [
                        'client_id' => $request->user()->id,
                        'report_name' => $request->report_name,
                        'doctor_name' => $request->doctor_name,
                        'report_date' => $request->report_date,
                        'report_time' => $request->report_time,
                        'description' => $request->description,                        
                        'report_images' => !empty($request->report_images) ? json_encode($request->report_images) : '',
                    ];
        try{
            $data = $this->lab_report_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Lab Report details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateLabReportDetails(UserLabReportRequest $request)
    {
        $data = array();
        $update_data = [
                        'report_name' => $request->report_name,
                        'doctor_name' => $request->doctor_name,
                        'report_date' => $request->report_date,
                        'report_time' => $request->report_time,
                        'description' => $request->description,
                        'report_images' => !empty($request->report_images) ? json_encode($request->report_images) : '',
                        ];
        try{
            $data = $this->lab_report_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Lab Report details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdLabReportDetails($id)
    {
        $data = array();
        $data = $this->lab_report_repo->getbyId($id);
        return self::sendSuccess($data, 'Lab Report details');
    }

    public function deleteLabReportDetails($id){
        $data = $this->lab_report_repo->getById($id);
        if(!empty($data)){
            try{
                $this->lab_report_repo->destroy($id); 
                return self::sendSuccess([], 'Lab Report details Deleted Successfully');
            }catch(\Exception $e){
               return self::sendError('', 'You can not delete this Lab Report details', 500);
            }
            
        }
        return self::sendError('', 'Lab Report not Deleted', 500);
    }

}
