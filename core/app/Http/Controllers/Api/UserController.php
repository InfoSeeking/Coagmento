<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Membership;
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
     * @api{get} /v1/users GetMultiple
     * @apiDescription Get a list of multiple users.
     * @apiGroup User
     * @apiName GetMultipleUsers
     * @apiParam {Integer} [project_id] Restrict to only users which are a member of this project.
     * @apiVersion 1.0.0
     */
    public function getMultiple(Request $req) {
        $validator = Validator::make($req->all(), [
            'project_id' => 'sometimes|exists:projects,id'
            ]);

        if ($validator->fails()) return ApiResponse::fromStatus(Status::fromValidator($validator));

        
        if ($req->has('project_id')) {
            $projectId = $req->input('project_id');
            $users = Membership::where('project_id', $projectId)->with('user')->get()->pluck('user');
        } else {
            $users = User::all();
        }
        
        return ApiResponse::fromStatus(Status::fromResult($users));
    }

    /**
     * @api{get} /v1/users/:id Get
     * @apiDescription Get information about a user.
     * @apiGroup User
     * @apiName GetUser
     * @apiParam {Integer} id The user id.
     * @apiVersion 1.0.0
     */
    public function get(Request $req, $user_id) {
        $status = Status::OK();
        $args = ['id' => $user_id];
        $validator = Validator::make($args, [
            'id' => 'integer'
            ]);

        if ($validator->fails()) return ApiResponse::fromStatus(Status::fromValidator($validator));

        $user = User::find($args['id']);

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
