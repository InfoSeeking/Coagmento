<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Utilities\Status;
use App\Utilities\ApiResponse;

class UserController extends Controller
{

    /**
     * @api{post} /v1/user Get
     * @apiDescription Get the currently logged in user.
     * @apiGroup User
     * @apiName GetUser
     * @apiParam {String} [auth_email] The user email to authenticate.
     * @apiParam {String} [auth_password] The user password to authenticate.
     * @apiVersion 1.0.0
     */
    public function get() {
        $status = Status::fromResult(['user' => Auth::user()]);
        return ApiResponse::fromStatus($status);
    }
}
