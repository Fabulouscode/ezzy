<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\SupportRequestRepository;
use App\Http\Requests\Api\SupportRequestRequest;

class SupportRequestController extends BaseApiController
{
    private $support_request_repo;

    public function __construct(SupportRequestRepository $support_request_repo)
    {
        parent::__construct();
        $this->support_request_repo = $support_request_repo;
    }


    public function getSupportRequest(Request $request)
    {
        $extra = array();
        $extra['status'] = $this->support_request_repo->status;
        $data = $this->support_request_repo->getSupportRequest($request);
        return self::sendSuccess($data, 'Support request list', $extra);
    }
   
    public function getSupportRequestInfo($id)
    {
        $extra = array();
        $data['status'] = $this->support_request_repo->status;
        $data['data'] = $this->support_request_repo->getbyIdedit($id);
        return self::sendSuccess($data, 'Support request list', $extra);
    } 
 
    public function addSupportReques(SupportRequestRequest $request)
    {
        $upload_file = '';
        
        if($request->file('attachment')) {          
            $file = $request->file('attachment');
            $storagePath = 'images/support_request';
            $upload_file = $this->support_request_repo->uploadFolderWiseFile($file, $storagePath);
        }
        $add_data = [
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'attachment' => $upload_file,
                    'status' => '0',
                ];

        try{
            $data = $this->support_request_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Support request add');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

}
