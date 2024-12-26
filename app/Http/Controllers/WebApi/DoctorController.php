<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WebApi\BaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorController extends BaseController
{
    public function getRandomDoctor(Request $request)
    {
        try {
            $doctor = User::with("categoryChild")->where("category_id", 4)->where("status", 0)->select("profile_image", "subcategory_id", "first_name", "last_name")->inRandomOrder()->take(4)->get()->append(["user_name"]);
            return $this->sendSuccess($doctor);
        } catch (Exception $e) {
            Log::info($e);
            return $this->sendError("Something went wrong please try again!");
        }
    }

    public function getAllDoctor(Request $request)
    {
        try {
            $logs = User::with("categoryChild")->where("category_id", 4)->where("status", 0)->select("profile_image", "subcategory_id", "first_name", "last_name")
                ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
            return $this->sendSuccess($logs);
        } catch (Exception $e) {
            Log::info($e);
            return $this->sendError("Something went wrong please try again!");
        }
    }
}
