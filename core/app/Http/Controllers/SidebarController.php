<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use App\Services\ProjectService;
use App\Services\MembershipService;


class SidebarController extends Controller
{

    protected $loginPath = '/sidebar/auth/login';
    protected $redirectPath = '/sidebar';
    protected $redirectTo = '/sidebar';
    protected $redirectAfterLogout = '/sidebar/auth/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	public function __construct(
        ProjectService $projectService,
        BookmarkService $bookmarkService,
        MembershipService $memberService) {
		$this->bookmarkService = $bookmarkService;
        $this->projectService = $projectService;
        $this->memberService = $memberService;
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
    	$bookmarks = $this->bookmarkService->getMultiple([
    		'project_id' => $projectId,
    		]);

        return view('sidebar.feed', [
            'bookmarks' => $bookmarks,
            'permission' => $memberStatus->getResult(),
            'project' => $projectStatus->getResult(),
            'user' => $user
            ]);
    }

    public function getSidebarLogin(Request $req) {
        return view('sidebar.auth');
    }

    public function demoLogin(Request $req) {
        $demoEmail = 'coagmento_demo@demo.demo';
        $demoUser = User::where('email', $demoEmail)->first();
        if (is_null($demoUser)) {
            $demoUser = $this->create([
                'name' => 'Coagmento Demo',
                'email' => $demoEmail,
                'password' => 'demo'
                ]);
        }
        Auth::login($demoUser, true);
        return redirect($this->redirectPath());
    }
}
