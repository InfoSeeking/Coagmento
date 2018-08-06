<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Project;
use App\Services\StageProgressService;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\DB;


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

            $user->last_login = Carbon::now();
            $user->save();



            $results = DB::tapostPretaskble('memberships')
                ->where('user_id', $user->id)
                ->get();
            if(!count($results)){
                $args = [
                    'title'=>'Demo Task',
                    'description'=>'Demo Task Description',
                    'private'=>true,
                ];
                $project = new Project($args);
                $project->creator_id = $user->id;
                $project->private = array_key_exists('private', $args) ? $args['private'] : false;
                $project->save();

                $owner = new Membership();
                $owner->user_id = $user->id;
                $owner->project_id = $project->id;
                $owner->level = 'o';
                $owner->save();

                $args = [
                    'title'=>'Task 1',
                    'description'=>'Task 1 Description',
                    'private'=>true,
                ];
                $project = new Project($args);
                $project->creator_id = $user->id;
                $project->private = array_key_exists('private', $args) ? $args['private'] : false;
                $project->save();

                $owner = new Membership();
                $owner->user_id = $user->id;
                $owner->project_id = $project->id;
                $owner->level = 'o';
                $owner->save();

                $args = [
                    'title'=>'Task 2',
                    'description'=>'Task 2 Description',
                    'private'=>true,
                ];
                $project = new Project($args);
                $project->creator_id = $user->id;
                $project->private = array_key_exists('private', $args) ? $args['private'] : false;
                $project->save();

                $owner = new Membership();
                $owner->user_id = $user->id;
                $owner->project_id = $project->id;
                $owner->level = 'o';
                $owner->save();
            }
            if($user->active && $user->is_admin){
                Auth::login($user, $req->has('remember'));
                $user->last_login = Carbon::now();
                $user->save();
                return redirect('/admin');
            } else if ($user->active && !$user->is_completed) {
//                dd($this->redirectPath());
                Auth::login($user, $req->has('remember'));
                $user->last_login = Carbon::now();
                $user->save();
//                return redirect()->intended($this->redirectPath());
            } else if($user->is_completed){
                return redirect($this->loginPath()) // Change this to redirect elsewhere
                ->withInput($req->only('email', 'remember'))
                    ->withErrors([
                        'active' => 'You have already completed the study.'
                    ]);
            }else {
                return redirect($this->loginPath()) // Change this to redirect elsewhere
                ->withInput($req->only('email', 'remember'))
                    ->withErrors([
                        'active' => 'You must be active to login.'
                    ]);
            }


//            public function getCurrentProject(){
//                $stage = $this->stageProgressService->getCurrentStage();
//                $stage->getResult();
//                $stage_id = $stage->getResult()->id;
//                Session::put('stage_id',$stage_id);
//
//                $project_id = 0;
//                if($stage_id <= 3){
//                    $project_id = $this->projectService->getMyFirstProject()->id;
//                }else if($stage_id <= 17){
//                    $project_id = $this->projectService->getMySecondProject()->id;
//                }else{
//                    $project_id = $this->projectService->getMyThirdProject()->id;
//                }
//
//                Session::put('project_id',$project_id);
//                return response()->json([
//                    'project_id'=>$project_id
//                ]);
//            }

            $current_project = $this->stageProgressService->getCurrentProject();
//            dd($current_project->getData()->{'project_id'});
            $project_id = $current_project->getData()->{'project_id'};

            $current_stage = $this->stageProgressService->getCurrentStage();

            $currentStage = $this->stageProgressService->getCurrentStage($req)->getResult();
            $currentStageProgress = $this->stageProgressService->getCurrentStageProgress($req)->getResult();
            $stage_data = [
                'stage_id'=>$currentStage->id,
                'timed'=>$currentStage->timed,
                'time_limit'=>$currentStage->time_limit,
                'time_start'=>$currentStageProgress->created_at,
            ];

            return ['logged_in'=>true,'id'=>$id,'name'=>$user->name, 'project_id'=>$project_id,'stage_data'=>$stage_data];
        }else{
            return ['logged_in'=>false];
        }

    }

    public function getLogoutSidebar(Request $req){

	    $user = Auth::user();
        $user->last_logout = Carbon::now();
        $user->save();
	    Auth::logout();

//	    Session::flush();
        return ['logged_in'=>Auth::check()];
    }

}
