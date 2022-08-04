<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Storage;

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
  
    public function fileRemoveStorage(Request $request){
        if(!empty($request->file_name)) {          
            $file_path = $request->file_name;
            $file_remove = $this->user_repo->removeFolderWiseFile($file_path);
            if(!empty($file_remove)){
                  return response()->json(['msg'=>'File remove success'], 200);
            }else{
                return response()->json(['msg'=>'File Not Exits'], 500);
            }
        }
         return response()->json(['msg'=>'File Path Not Exits'], 500);
    }
}
