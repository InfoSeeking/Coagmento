<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\ProjectService;
use App\Services\BookmarkService;
use App\Services\SnippetService;
use App\Utilities\Status;

class WorkspaceController extends Controller
{
    public function __construct(
        ProjectService $projectService,
        BookmarkService $bookmarkService,
        SnippetService $snippetService) {
        $this->projectService = $projectService;
        $this->bookmarkService = $bookmarkService;
        $this->snippetService = $snippetService;
    }

    public function index() {
        $projects = $this->projectService->getMultiple();
        return view('workspace.home', [
            'projects' => $projects
            ]);
    }

    public function viewProject(Request $req, $projectId) {
        $bookmarksStatus = $this->bookmarkService->getMultiple(['project_id' => $projectId]);
        if (!$bookmarksStatus->isOK()) {
            return $bookmarkStatus->asRedirect('workspace');
        }

        $snippetStatus = $this->snippetService->getMultiple(['project_id' => $projectId]);
        if (!$snippetStatus->isOK()) {
            return $snippetStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        return view('workspace.project', [
            'bookmarks' => $bookmarksStatus->getResult(),
            'snippets' => $snippetStatus->getResult(),
            'project' => $projectStatus->getResult()
            ]);
    }

    public function viewBookmark(Request $req, $projectId, $bookmarkId) {
        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }
        $bookmarkStatus = $this->bookmarkService->get($bookmarkId);
        if (!$bookmarkStatus->isOK()) {
            return $bookmarkStatus->asRedirect('workspace');
        }
        return view('workspace.bookmark', [
            'project' => $projectStatus->getResult(),
            'bookmark' => $bookmarkStatus->getResult()
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
    private $bookmarkService;
    private $snippetService;
}
