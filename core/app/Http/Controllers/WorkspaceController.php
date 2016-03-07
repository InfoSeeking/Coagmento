<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

use Auth;
use Session;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Services\DocService;
use App\Services\ProjectService;
use App\Services\BookmarkService;
use App\Services\SnippetService;
use App\Services\PageService;
use App\Services\QueryService;
use App\Services\MembershipService;
use App\Utilities\Status;

class WorkspaceController extends Controller
{
    public function __construct(
        ProjectService $projectService,
        BookmarkService $bookmarkService,
        SnippetService $snippetService,
        PageService $pageService,
        MembershipService $memberService,
        DocService $docService,
        PageService $pageService,
        QueryService $queryService) {
        $this->projectService = $projectService;
        $this->bookmarkService = $bookmarkService;
        $this->snippetService = $snippetService;
        $this->pageService = $pageService;
        $this->memberService = $memberService;
        $this->docService = $docService;
        $this->pageService = $pageService;
        $this->queryService = $queryService;
    }

    public function showProjectCreate() {
        return view('workspace.projects.create', [
            'user' => Auth::user()
            ]);
    }

    public function showProjects() {

        $projects = $this->projectService->getMyProjects();
        return view('workspace.projects.index', [
            'projects' => $projects,
            'user' => Auth::user(),
            'type' => 'mine'
            ]);
    }

    public function showShared() {
        $projectsWithMemberships = $this->projectService->getSharedProjects();
        return view('workspace.projects.index', [
            'projects' => $projectsWithMemberships,
            'user' => Auth::user(),
            'type' => 'shared'
            ]);
    }

    public function viewProjectSettings(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'o', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

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

        $sharedUsersStatus = $this->projectService->getSharedUsers($projectId);
        if (!$sharedUsersStatus->isOK()) return $sharedUsersStatus->asRedirect('workspace');

        return view('workspace.projects.settings', [
            'bookmarks' => $bookmarksStatus->getResult(),
            'snippets' => $snippetStatus->getResult(),
            'pages' => $pageStatus->getResult(),
            'project' => $projectStatus->getResult(),
            'sharedUsers' => $sharedUsersStatus->getResult(),
            'user' => Auth::user()
            ]);
    }

    public function viewProject(Request $req, $projectId) {
        Log::debug("view projects");
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) return $permissionStatus->asRedirect('workspace');

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) return $projectStatus->asRedirect('workspace');

        $bookmarksStatus = $this->bookmarkService->getMultiple(['project_id' => $projectId]);
        if (!$bookmarksStatus->isOK()) return $bookmarksStatus->asRedirect('workspace');

        $snippetStatus = $this->snippetService->getMultiple(['project_id' => $projectId]);
        if (!$snippetStatus->isOK()) return $snippetStatus->asRedirect('workspace');

        $sharedUsersStatus = $this->projectService->getSharedUsers($projectId);
        if (!$sharedUsersStatus->isOK()) return $sharedUsersStatus->asRedirect('workspace');
        
        return view('workspace.projects.view', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user(),
                'bookmarks' => $bookmarksStatus->getResult(),
                'snippets' => $snippetStatus->getResult(),
                'sharedUsers' => $sharedUsersStatus->getResult(),
            ]);
    }

    public function viewProjectBookmarks(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        $bookmarksStatus = $this->bookmarkService->getMultiple(['project_id' => $projectId]);
        if (!$bookmarksStatus->isOK()) return $bookmarksStatus->asRedirect('workspace');

        $sharedUsersStatus = $this->projectService->getSharedUsers($projectId);
        if (!$sharedUsersStatus->isOK()) return $sharedUsersStatus->asRedirect('workspace');

        return view('workspace.projects.bookmarks', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user(),
                'sharedUsers' => $sharedUsersStatus->getResult(),
                'bookmarks' => $bookmarksStatus->getResult()
            ]);
    }

    public function viewProjectSnippets(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        $snippetsStatus = $this->snippetService->getMultiple(['project_id' => $projectId]);
        if (!$snippetsStatus->isOK()) {
            return $snippetsStatus->asRedirect('workspace');
        }

        return view('workspace.projects.snippets', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user(),
                'snippets' => $snippetsStatus->getResult()
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

    public function viewChat(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        return view('workspace.projects.chat', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user()
            ]);
    }

    public function viewDoc(Request $req, $projectId, $docId) {
        // Viewing documents is a special case until we can figure out how to have read-only view
        // of documents.
        $permissionStatus = $this->memberService->checkPermission($projectId, 'w', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        $docStatus = $this->docService->getWithSession(['doc_id' => $docId]);
        if (!$docStatus->isOK()) {
            return $docStatus->asRedirect('workspace');
        }

        return view('workspace.projects.doc', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user(),
                'doc' => $docStatus->getResult()
            ]);
    }

    public function viewDocs(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) {
            return $permissionStatus->asRedirect('workspace');
        }

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) {
            return $projectStatus->asRedirect('workspace');
        }

        return view('workspace.projects.docs', [
                'project' => $projectStatus->getResult(),
                'permission' => $permissionStatus->getResult(),
                'user' => Auth::user()
            ]);
    }

    public function viewHistory(Request $req, $projectId) {
        $permissionStatus = $this->memberService->checkPermission($projectId, 'r', Auth::user());
        if (!$permissionStatus->isOK()) return $permissionStatus->asRedirect('workspace');

        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) return $projectStatus->asRedirect('workspace');

        $pageStatus = $this->pageService->getMultiple(['project_id' => $projectId]);
        if (!$pageStatus->isOK()) return $pageStatus->asRedirect('workspace');

        $queryStatus = $this->queryService->getMultiple(['project_id' => $projectId]);
        if (!$queryStatus->isOK()) return $queryStatus->asRedirect('workspace');

        $sharedUsersStatus = $this->projectService->getSharedUsers($projectId);
        if (!$sharedUsersStatus->isOK()) return $sharedUsersStatus->asRedirect('workspace');

        return view('workspace.projects.history', [
            'project' => $projectStatus->getResult(),
            'permission' => $permissionStatus->getResult(),
            'user' => Auth::user(),
            'sharedUsers' => $sharedUsersStatus->getResult(),
            'pages' => $pageStatus->getResult(),
            'queries' => $queryStatus->getResult()
            ]);
    }
}
