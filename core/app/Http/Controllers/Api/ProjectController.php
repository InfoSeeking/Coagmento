<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\ProjectService;
use App\Services\TagService;
use App\Utilities\ApiResponse;

class ProjectController extends Controller
{
    function __construct(
        ProjectService $projectService,
        TagService $tagService) {
        $this->projectService = $projectService;
        $this->tagService = $tagService;
    }
	/**
	 * @api{get} /v1/projects Get Multiple
	 * @apiDescription Returns a list of projects of which the user has membership.
	 * @apiGroup Project
	 * @apiName GetProjects
     * @apiPermission read
     * @apiVersion 1.0.0
	 */
    function index() {
        $projects = $this->projectService->getMultiple();
        return ApiResponse::fromResult($projects);
    }

    /**
	 * @api{get} /v1/projects/:id Get
	 * @apiDescription Returns a single project and the user's membership.
     * If the project is public, the user does not need any permissions.
	 * @apiGroup Project
	 * @apiName GetProject
     * @apiPermission read
     * @apiVersion 1.0.0
	 */
    function get($id) {
        $projectStatus = $this->projectService->get($id);
        return ApiResponse::fromStatus($projectStatus);
    }

    /**
	 * @api{post} /v1/projects/ Create
	 * @apiDescription Creates a single project and sets the user as owner.
	 * @apiGroup Project
	 * @apiName CreateProject
     * @apiParam {String} title
     * @apiParam {Boolean} [private=false] Private projects are not publicly searchable.
     * @apiPermission write
     * @apiVersion 1.0.0
	 */
    function create(Request $req) {
        $status = $this->projectService->create($req->all());
        return ApiResponse::fromStatus($status);
    }

    /**
	 * @api{delete} /v1/projects/:id Delete
	 * @apiDescription Deletes a project if the user is the owner.
	 * @apiGroup Project
	 * @apiName DeleteProject
     * @apiPermission write
     * @apiVersion 1.0.0
	 */
    function delete(Request $req, $id) {
    	$status = $this->projectService->delete(['id' => $id]);
    	return ApiResponse::fromStatus($status);
    }

    /**
     * @api{put} /v1/projects/:id Update
     * @apiDescription Updates a project.
     * @apiGroup Project
     * @apiName UpdateProject
     * @apiParam {String} [title]
     * @apiPermission write
     * @apiVersion 1.0.0
     */
    function update(Request $req, $id) {
    	$args = array_merge($req->all(), ['id' => $id]);
    	$status = $this->projectService->update($args);
    	return ApiResponse::fromStatus($status);
    }

    /**
     * @api{get} /v1/projects/:id/tags Get Tags
     * @apiDescription Get a list of all tags used in this project.
     * @apiGroup Project
     * @apiName GetProjectTags
     * @apiPermission read
     * @apiVersion 1.0.0
     */
    function getTags(Request $req, $id) {
        $status = $this->tagService->getMultiple(['project_id' => $id]);
        return ApiResponse::fromStatus($status);
    }

    private $projectService;
    private $tagService;
}
