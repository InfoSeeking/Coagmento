<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Utilities\Status;
use App\Utilities\StatusCodes;
use App\Utilities\ApiResponse;

class UserController extends Controller
{

    /**
     * @api{get} /v1/users/current Get Current
     * @apiDescription Get the currently logged in user.
     * @apiGroup User
     * @apiName GetLoggedIn
     * @apiVersion 1.0.0
     */
    public function getCurrent() {
        $status = Status::fromResult(['user' => Auth::user()]);
        return ApiResponse::fromStatus($status);
    }

    /**
     * @api{get} /v1/users Get
     * @apiDescription Get information about a user.
     * @apiGroup User
     * @apiName GetUser
     * @apiParam {String} email
     * @apiVersion 1.0.0
     */
    public function get(Request $req) {
        $status = Status::OK();
        $validator = Validator::make($req->all(), [
            'email' => 'required|email'
            ]);

        if ($validator->fails()) {
            return ApiResponse::fromStatus(Status::fromValidator($validator));
        }

        $user = User::where('email', $req->input('email'))->first();

        if (is_null($user)) {
            return ApiResponse::fromStatus(
                Status::fromError('User not found.', StatusCodes::NOT_FOUND));
        }

        return ApiResponse::fromStatus(Status::fromResult(['user' => $user]));
    }

    /**
     * @api{post} /v1/users Create
     * @apiDescription Create a new user.
     * @apiGroup User
     * @apiName CreateUser
     * @apiParam {String} email
     * @apiParam {String} password
     * @apiParam {String} name
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        // TODO We should consider somehow adding a captcha to prevent spamming.
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return ApiResponse::fromStatus(Status::fromValidator($validator));
        }

        $user = User::create([
            'name' => $req->input('name'),
            'email' => $req->input('email'),
            'password' => bcrypt($req->input('password')),
        ]);

        return ApiResponse::fromStatus(Status::fromResult(['user' => $user]));
    }
}
