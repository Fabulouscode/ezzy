<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class FileUploadController extends Controller
{
    private $user_repo;

    public function __construct(UserRepository $user_repo)
    {
        $this->user_repo = $user_repo;
    }

    public function fileUploadStorage(Request $request){
        if($request->hasFile('file') && !empty($request->folder_name)) {          
            $file = $request->file('file');
            $storagePath = 'images/'.$request->folder_name;
            $data['name'] = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
            $data['url'] = url('storage/'.$data['name']);
            return response()->json($data, 200);
        }
    }
}
