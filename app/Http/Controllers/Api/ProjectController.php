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
        $projects = ProjectService::getForUser();
        return ApiResponse::fromResults($projects);
    }

    function create(Request $req) {
        $status = ProjectService::create($req);
        return ApiResponse::fromStatus($status);
    }
}
