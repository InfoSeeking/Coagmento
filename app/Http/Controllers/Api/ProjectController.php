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
    function index() {
        $projects = ProjectService::getMultiple();
        return ApiResponse::fromResult($projects);
    }

    function get($id) {
        $projectStatus = ProjectService::get($id);
        return ApiResponse::fromStatus($projectStatus);
    }

    function create(Request $req) {
        $status = ProjectService::create($req);
        return ApiResponse::fromStatus($status);
    }

    function delete(Request $req, $id) {
    	$status = ProjectService::delete($req, $id);
    	return ApiResponse::fromStatus($status);
    }
}
