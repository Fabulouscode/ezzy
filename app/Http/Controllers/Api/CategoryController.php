<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;

class CategoryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHCPMainTypes(Request $request){
        $data = array();
        $ids=['1','2','3'];
        $data = $this->category_repo->getByMultipleParentIds($ids);
        return self::sendSuccess($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHCPSubTypes($id){
        $data = array();
        $data = $this->category_repo->getByParentId($id);
        return self::sendSuccess($data);
    }

   
}
