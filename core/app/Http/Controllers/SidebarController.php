<?php

namespace App\Http\Controllers;

use App\Services\StageProgressService;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use App\Services\MembershipService;
use App\Services\ProjectService;
use App\Services\SnippetService;
use App\Traits\AuthenticateCoagmentoUsers;


class SidebarController extends Controller
{

    protected $loginPath = '/sidebar/auth/login';
    protected $redirectPath = '/sidebar';
    protected $redirectTo = '/sidebar';
    protected $redirectAfterLogout = '/sidebar/auth/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins, AuthenticateCoagmentoUsers;

	public function __construct(
        ProjectService $projectService,
        BookmarkService $bookmarkService,
        MembershipService $memberService,
        SnippetService $snippetService,
        StageProgressService $stageProgressService
        ) {
		$this->bookmarkService = $bookmarkService;
        $this->projectService = $projectService;
        $this->memberService = $memberService;
        $this->snippetService = $snippetService;
        $this->stageProgressService = $stageProgressService;
	}
	public function getProjectSelection() {
        $projects = $this->projectService->getMultiple();
		return view('sidebar.select', [
            'projects' => $projects,
            'user' => Auth::user()
            ]);
	}
    public function getFeed(Request $req, $projectId) {
    	$user = Auth::user();
        $projectStatus = $this->projectService->get($projectId);
        if (!$projectStatus->isOK()) return $projectStatus->asRedirect('sidebar');

        $memberStatus = $this->memberService->checkPermission($projectId, 'r', $user);
        if (!$memberStatus->isOK()) return $memberStatus->asRedirect('sidebar');

        $sharedUsersStatus = $this->projectService->getSharedUsers($projectId);
        if (!$sharedUsersStatus->isOK()) return $sharedUsersStatus->asRedirect('sidebar');

    	$bookmarkStatus = $this->bookmarkService->getMultiple(['project_id' => $projectId]);
        if (!$bookmarkStatus->isOK()) return $bookmarkStatus->asRedirect('sidebar');

        $snippetStatus = $this->snippetService->getMultiple(['project_id' => $projectId]);
        if (!$snippetStatus->isOK()) return $snippetStatus->asRedirect('sidebar');

        return view('sidebar.feed', [
            'bookmarks' => $bookmarkStatus->getResult(),
            'snippets' => $snippetStatus->getResult(),
            'permission' => $memberStatus->getResult(),
            'project' => $projectStatus->getResult(),
            'user' => $user,
            'sharedUsers' => $sharedUsersStatus->getResult()
            ]);
    }

    public function getSidebarLogin(Request $req) {
        return view('sidebar.auth');
    }


    public function postLoginSidebar(Request $req) {
        // Check if the email provided is an old Coagmento username.
        $email = $req->input('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // All imported old Coagmento users are assigned to
            // a placeholder @coagmento.org email address for consistency.
            $email .= '@coagmento.org';
            $req->merge(['email' => $email]);
        }

        $password = $req->input('password');
        // Proceed with standard login.
        $logged_in = Auth::attempt(['email' => $email, 'password' => $password]);


        if($logged_in){
            $id = Auth::id();
            $user = Auth::user();
            return ['logged_in'=>true,'id'=>$id,'name'=>$user->name];
        }else{
            return ['logged_in'=>false];
        }

    }

    public function getLogoutSidebar(Request $req){
	    Auth::logout();
//	    Session::flush();
        return ['logged_in'=>Auth::check()];
    }

}
