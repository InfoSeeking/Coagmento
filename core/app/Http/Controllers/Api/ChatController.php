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
    /**
     * @api{get} /v1/chatMessages Get Multiple
     * @apiDescription Gets a list of the chat messages for this project.
     * @apiGroup Chat
     * @apiName GetMultiple
     * @apiPermission read
     * @apiParam {Integer} project_id The id of the corresponding project.
     * @apiVersion 1.0.0
     * @apiExample Example Usage
     * curl "http://localhost:8000/api/v1/chatMessages?auth_email=coagmento_demo@demo.demo&auth_password=demo&project_id=300"
     * @apiSuccessExample {json} Success Response
     *   {
     *     "status": "ok",
     *     "errors": {
     *       "input": [],
     *       "general": []
     *     },
     *     "result": [
     *       {
     *         "project_id": 300,
     *         "user_id": 298,
     *         "message": "Hello!",
     *         "created_at": "2015-11-25 02:40:38",
     *         "updated_at": "2015-11-25 02:40:38",
     *         "user": {
     *           "id": 298,
     *           "name": "Kevin Albertson",
     *           "email": "k_albertson@live.com",
     *           "created_at": "2015-11-25 02:06:56",
     *           "updated_at": "2015-11-25 02:06:56"
     *         }
     *       }
     *     ]
     *   }
     */
    public function getMultiple(Request $req) {
        $chatStatus = $this->chatService->getMultiple($req->all());
        return ApiResponse::fromStatus($chatStatus);
    }

    /**
     * @api{post} /v1/chatMessages Create
     * @apiDescription Create a new chat message for the project.
     * @apiGroup Chat
     * @apiName Create
     * @apiPermission write
     * @apiParam {Integer} project_id The id of the corresponding project.
     * @apiParam {String} message The chat message.
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $chatStatus = $this->chatService->create($req->all());
        return ApiResponse::fromStatus($chatStatus);
    }
}
