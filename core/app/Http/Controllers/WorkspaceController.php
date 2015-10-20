<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\ProjectService;
use App\Services\BookmarkService;
use App\Services\SnippetService;
use App\Services\PageService;
use App\Utilities\Status;

class WorkspaceController extends Controller
{
    public function __construct(
        ProjectService $projectService,
        BookmarkService $bookmarkService,
        SnippetService $snippetService,
        PageService $pageService) {
        $this->projectService = $projectService;
        $this->bookmarkService = $bookmarkService;
        $this->snippetService = $snippetService;
        $this->pageService = $pageService;
    }

    public function showProjectCreate() {
        return view('workspace.projects.create');
    }

    public function showProjects() {
        $projects = $this->projectService->getMultiple();
        return view('workspace.projects.index', [
            'projects' => $projects
            ]);
    }

    public function viewProject(Request $req, $projectId) {
        $bookmarksStatus = $this->bookmarkService->getMultiple(['project_id' => $projectId]);
        if (!$bookmarksStatus->isOK()) {
            return $bookmarksStatus->asRedirect('workspace');
        }

        $snippetStatus = $this->snippetService->getMultiple(['project_id' => $projectId]);
        if (!$snippetStatus->isOK()) {
            return $snippetStatus->asRedirect('workspace');
        }

        $pageStatus = $this->pageService->getMultiple(['project_id' => $projectId]);
        if (!$pageStatus->isOK()) {
            return $pageStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        return view('workspace.projects.view', [
            'bookmarks' => $bookmarksStatus->getResult(),
            'snippets' => $snippetStatus->getResult(),
            'pages' => $pageStatus->getResult(),
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
        $private = $req->input('visibility') == 'private';
        $args = array_merge($req->all(), ['private' => $private]);
        $projectStatus = $this->projectService->create($args);
        return $projectStatus->asRedirect('workspace/projects', ['New project created']);
    }

    public function updateProject(){}
    
    public function deleteProject(Request $req, $projectId) {
        $status = $this->projectService->delete(['id' => $projectId]);
        return $status->asRedirect('workspace');
    }
}
