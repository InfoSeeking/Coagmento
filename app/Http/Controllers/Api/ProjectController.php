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
    function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }
	/**
	 * @api{get} /v1/projects
	 * @apiDescription Returns a list of projects which the user has membership.
	 * @apiGroup Project
	 * @apiName GetProjects
	 */
    function index() {
        $projects = $this->projectService->getMultiple();
        return ApiResponse::fromResult($projects);
    }

    /**
	 * @api{get} /v1/projects/:id
	 * @apiDescription Returns a single project and the user's membership.
	 * @apiGroup Project
	 * @apiName GetProject
	 */
    function get($id) {
        $projectStatus = $this->projectService->get($id);
        return ApiResponse::fromStatus($projectStatus);
    }

    /**
	 * @api{post} /v1/projects/
	 * @apiDescription Creates a single project and sets the user as owner.
	 * @apiGroup Project
	 * @apiName CreateProject
	 */
    function create(Request $req) {
        $status = $this->projectService->create($req);
        return ApiResponse::fromStatus($status);
    }

    /**
	 * @api{delete} /v1/projects/:id
	 * @apiDescription Deletes a project if the user is the owner.
	 * @apiGroup Project
	 * @apiName DeleteProject
	 */
    function delete(Request $req, $id) {
    	$status = $this->projectService->delete(['id' => $id]);
    	return ApiResponse::fromStatus($status);
    }

    function update(Request $req, $id) {
    	$args = array_merge($req->all(), ['id' => $id]);
    	$status = $this->projectService->update($args);
    	return ApiResponse::fromStatus($status);
    }

    private $projectService;
}
