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
     * @apiParam {String} title The project title.
     * @apiParam {String} [description] The project description.
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
     * @apiPermission owner
     * @apiVersion 1.0.0
	 */
    function delete(Request $req, $id) {
    	$status = $this->projectService->delete(['id' => $id]);
    	return ApiResponse::fromStatus($status);
    }

    /**
     * @api{delete} /v1/projects Delete Projects
     * @apiDescription Deletes multiple projects if the user is the owner.
     * @apiGroup Project
     * @apiName DeleteProjects
     * @apiParam {Integer[]} ids
     * @apiPermission owner
     * @apiVersion 1.0.0
     */
    function deleteMultiple(Request $req) {
        $status = $this->projectService->deleteMultiple($req->all());
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

    /**
     * @api{post} /v1/projects/:id/share Share Project
     * @apiDescription Share a project with another user.
     * @apiPermission own
     * @apiParam {String} [user_id] The id of the user (required if user_email is not present)
     * @apiParam {String} [user_email] The email of the user (required if user_id is not present)
     * @apiParam {String} permission Can be one of {w,r,o} representing write, read, and owner permissions.
     * @apiGroup Project
     * @apiName ShareProject
     * @apiVersion 1.0.0
     */
    function share(Request $req, $id) {
        $args = array_merge($req->all(), ['id' => $id]);
        $status = $this->projectService->share($args);
        return ApiResponse::fromStatus($status);
    }
}
