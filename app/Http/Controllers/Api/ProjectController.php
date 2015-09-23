<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\ProjectService;
use App\Utilities\ApiResponse;

class ProjectController extends Controller
{
	/**
	 * @api{get} /v1/projects
	 * @apiDescription Returns a list of projects which the user has membership.
	 * @apiGroup Project
	 * @apiName GetProjects
	 */
    function index() {
        $projects = ProjectService::getMultiple();
        return ApiResponse::fromResult($projects);
    }

    /**
	 * @api{get} /v1/projects/:id
	 * @apiDescription Returns a single project and the user's membership.
	 * @apiGroup Project
	 * @apiName GetProject
	 */
    function get($id) {
        $projectStatus = ProjectService::get($id);
        return ApiResponse::fromStatus($projectStatus);
    }

    /**
	 * @api{post} /v1/projects/
	 * @apiDescription Creates a single project and sets the user as owner.
	 * @apiGroup Project
	 * @apiName CreateProject
	 */
    function create(Request $req) {
        $status = ProjectService::create($req);
        return ApiResponse::fromStatus($status);
    }

    /**
	 * @api{delete} /v1/projects/:id
	 * @apiDescription Deletes a project if the user is the owner.
	 * @apiGroup Project
	 * @apiName DeleteProject
	 */
    function delete(Request $req, $id) {
    	$status = ProjectService::delete($req, $id);
    	return ApiResponse::fromStatus($status);
    }
}
