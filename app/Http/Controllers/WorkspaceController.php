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
    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    public function index() {
        $projects = $this->projectService->getMultiple();
        return view('workspace.home', [
            'projects' => $projects
            ]);
    }

    public function viewProject(Request $req, $projectId) {
        $projects = $this->projectService->getMultiple();
        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }
        return view('workspace.project', [
            'projects' => $projects,
            'project' => $projectStatus->getResult()
            ]);
    }

    public function createProject(Request $req) {
        $projectStatus = $this->projectService->create($req->all());
        return $projectStatus->asRedirect('workspace');
    }

    public function updateProject(){}
    
    public function deleteProject(Request $req, $projectId) {
        $status = $this->projectService->delete(['id' => $projectId]);
        return $status->asRedirect('workspace');
    }

    private $projectService;
}
