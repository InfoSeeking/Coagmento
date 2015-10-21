<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

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

    public function showHome() {
        return view('workspace.home');
    }

    public function showProjectCreate() {
        return view('workspace.projects.create');
    }

    public function showProjects() {
        $projects = $this->projectService->getMyProjects();
        return view('workspace.projects.index', [
            'projects' => $projects,
            'user' => Auth::user()
            ]);
    }

    public function showShared() {
        $projectsWithMemberships = $this->projectService->getSharedProjects();
        return view('workspace.projects.index', [
            'projects' => $projectsWithMemberships,
            'user' => Auth::user()
            ]);
    }

    public function viewProjectSettings(Request $req, $projectId) {
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

        $sharedUsers = $this->projectService->getSharedUsers($projectId);

        return view('workspace.projects.settings', [
            'bookmarks' => $bookmarksStatus->getResult(),
            'snippets' => $snippetStatus->getResult(),
            'pages' => $pageStatus->getResult(),
            'project' => $projectStatus->getResult(),
            'sharedUsers' => $sharedUsers
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
        $project = $projectStatus->getResult();
        return $projectStatus->asRedirect(
            'workspace/projects/' . $project->id . '/settings', 
            ['Project ' . $project->name . ' created']);
    }

    public function updateProject(){}
    
    public function deleteProject(Request $req, $projectId) {
        $status = $this->projectService->delete(['id' => $projectId]);
        return $status->asRedirect('workspace');
    }

    public function demoLogin() {
        $demoUser = User::firstOrCreate([
            'email' => 'demo@demo.demo',
            'password' => 'demo'
            ]);
        Auth::login($demoUser, true);
        return redirect('workspace');
    }
}
