<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SupportChatRepository;
use App\Repositories\UserRepository;
use App\Repositories\SupportRequestRepository;
use App\Http\Requests\Admin\SupportChatRequest;

class SupportChatController extends Controller
{
    private $user_repo, $support_request_repo, $support_chat_repo;

    public function __construct(UserRepository $user_repo, SupportRequestRepository $support_request_repo, SupportChatRepository $support_chat_repo)
    {
        $this->support_request_repo = $support_request_repo;
        $this->support_chat_repo = $support_chat_repo;
        $this->user_repo = $user_repo;
    }
}
