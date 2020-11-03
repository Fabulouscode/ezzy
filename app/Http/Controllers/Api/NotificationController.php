<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    private $user_repo, $notification_repo;

    public function __construct(UserRepository $user_repo, NotificationRepository $notification_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
    }

    public function FunctionName(Type $var = null)
    {
        # code...
    }
    
}
