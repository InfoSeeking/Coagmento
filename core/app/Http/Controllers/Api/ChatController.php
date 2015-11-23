<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Utilities\ApiResponse;
use App\Services\ChatService;

class ChatController extends Controller
{
    public function __construct(ChatService $chatService) {
        $this->chatService = $chatService;
    }

    public function getMultiple(Request $req) {
        $chatStatus = $this->chatService->getMultiple($req->all());
        return ApiResponse::fromStatus($chatStatus);
    }

    public function create(Request $req) {
        $chatStatus = $this->chatService->create($req->all());
        return ApiResponse::fromStatus($chatStatus);
    }
}
