<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\ProjectService;
use App\Services\BookmarkService;
use App\Utilities\Status;

class WorkspaceController extends Controller
{
    public function index() {
        $projects = ProjectService::getMultiple();
        return view('workspace.home', [
            'projects' => $projects
            ]);
    }

    public function viewProject($projectId) {
        $projects = ProjectService::getMultiple();
        $project = ProjectService::get($projectId);

    }

    public function createProject(Request $req) {
        $projectStatus = ProjectService::create($req->all());
        return $projectStatus->asRedirect('workspace');
    }

    public function updateProject(){}
    public function deleteProject(Request $req, $projectId) {
        $status = ProjectService::delete(['id' => $projectId]);
        return $status->asRedirect('workspace');
    }
}
