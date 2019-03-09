<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ParticipantController;
use App\Models\User;
use App\Models\Project;
use App\Models\Membership;
use App\Models\Demographic;
use App\Models\Stage;
use App\Services\ProjectService;
use App\Services\StageProgressService;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    protected $redirectPath = '/stages/next';
    protected $redirectTo = '/stages/next';
//    protected $redirectPath = '/workspace';
//    protected $redirectTo = '/workspace';
    protected $redirectAfterLogout = '/auth/login?'; //changed from https//coagmento.org to localhost:8000/auth/login? for quick debugging
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(
        ProjectService $projectService,
        StageProgressService $stageProgressService
    )
    {
        $this->projectService = $projectService;
        $this->stageProgressService = $stageProgressService;
        $this->middleware('guest', ['except' => ['getLogout','checkLoggedIn']]);
//        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
//            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function isLoggedIn(Request $req){
        return Auth::user();
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $password_raw = str_random(8);
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_raw' => $password_raw,
            'password' => bcrypt($password_raw),
            'is_admin'=>false,
//            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Creates a new user using random name and credentials
     */
    public static function createRandom(){
        $name=str_random(6);
        $email=str_random(5);
        $email.='@example.com';
        $password_raw = str_random(8);

        return User::create([
            'name' => $name,
            'email' => $email,
            'password_raw' => $password_raw,
            'password' => bcrypt($password_raw),
            'is_admin'=>false,
        ]);

    }

    /**
     * Called when the user is authenticated
     */
    protected function authenticated(Request $req, User $user) {
        if ($req->has('after_login_redirect')) {
            dd('dd');
            return redirect($req->input('after_login_redirect'));
        } else {
            return redirect($this->redirectPath());
        }
    }


    public function getStudyWelcome(Request $req){
        return view('studywelcome');
    }

    public function postStudyWelcome(Request $req){
        return redirect('auth/consent');
    }

    public function getConsent(Request $req){
        return view('consent');
    }

    public function getConfirmation(Request $req){
        return view('auth.confirmation');
    }

    public function postConsent(Request $req){
        $this->validate($req, [
            'consent_datacollection' => 'required',
            'consent_audio' => 'required',
        ],['required'=>'Please check the :attribute.']);
        $consent_datacollection = $req->input('consent_datacollection');
        $consent_audio = $req->input('consent_audio');
        $consent_furtheruse = $req->input('consent_furtheruse');
        return redirect('auth/register')->with('consent_datacollection',$consent_datacollection)->with('consent_audio',$consent_audio)->with('consent_furtheruse',$consent_furtheruse);
    }

    public function checkLoggedIn(Request $req){
        $user = Auth::user();
        if(is_null($user)){
            return ['logged_in'=>false];
        }
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


        return ['logged_in'=>true,'id'=>$user->id,'name'=>$user->name, 'project_id'=>$project_id,'stage_data'=>$stage_data];
    }

    /*
     * Overriding standard getLogout method
     */
    public function getLogout()
    {
        $user = Auth::user();
        $user->last_logout = Carbon::now();
        $user->save();
        Auth::logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function postLoginWithOldCoagmentoSupport(Request $req) {

        $email = $req->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // All imported old Coagmento users are assigned to
            // a placeholder @coagmento.org email address for consistency.
            $email .= '@coagmento.org';
            $req->merge(['email' => $email]);
        }
        // Proceed with standard login.


        $this->validate($req, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $this->getCredentials($req);

        // This section is the only change
        if (Auth::validate($credentials)) {
            $user = Auth::getLastAttempted();


            $results = DB::table('memberships')
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

                $stageID = Stage::where('weight', 0)->first()->id;

                //dd($stageID);
                Auth::login($user, $req->has('remember'));
                $user->last_login = Carbon::now();
                $user->save();
                ParticipantController::start($stageID);

            } else if($user->is_completed){
                return redirect($this->loginPath()) // Change this to redirect elsewhere
                ->withInput($req->only('email', 'remember'))
                    ->withErrors([
                        'active' => 'You have already completed the study.'
                    ]);
            }else {
                return view('participant.inactive');
                /*return redirect($this->loginPath()) // Change this to redirect elsewhere
                ->withInput($req->only('email', 'remember'))
                    ->withErrors([
                        'active' => 'You must be active to login.'
                    ]);*/
            }
        }

        return redirect($this->loginPath())
            ->withInput($req->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);

        return $this->postLogin($req);


//        // Check if the email provided is an old Coagmento username.
//        $email = $req->input('email');
//        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//            // All imported old Coagmento users are assigned to
//            // a placeholder @coagmento.org email address for consistency.
//            $email .= '@coagmento.org';
//            $req->merge(['email' => $email]);
//        }
//        // Proceed with standard login.
//        return $this->postLogin($req);
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
        return $this->authenticated($req, $demoUser);
    }

    public function postRegister(Request $request)
    {







      $rules = [
          'age' => 'required',
          'gender' => 'required',
          'major' => 'required',
          'english_first' => 'required',
          'native_language' => 'required_if:english_first,No',
          'search_experience' => 'required',
          'search_frequency' => 'required',
          'nonsearch_frequency' => 'required',
          'name'=>'required',
          'email'=>'required',
          'study_date'=>'required',
//          'g-recaptcha-response' => 'required|captcha',
//            'password'=>'required'
      ];

      $email = $request->input('email');

      if(strrpos($email, '.ru') === strlen($email)-strlen('.ru')){
          $rules['valid_email'] = 'required';
      }


        $this->validate($request, $rules);

        $validator = $this->validator($request->all());

        $validator->after(function($validator) {
            if (User::all()->count() >=66) {
                $validator->errors()->add('field', 'Number of users has reached capacity.');
            }
        });
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
//        else if(User::all()->count() >=10){
//            $validator->errors()->add('messageArea','Number of users has reached capacity.');
////            $this->throwValidationException($request, $validator);
//            throw new ValidationException($validator);
//        }


        $user = $this->create($request->all());

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

        Demographic::create([
            'user_id'=>$user->id,
            'age'=>$request->input('age'),
            'gender'=>$request->input('gender'),
            'major'=>$request->input('major'),
            'native_language'=>$request->input('native_language'),
            'english_first'=>$request->input('english_first'),
            'language_first'=>$request->input('language_first'),
            'search_experience'=>$request->input('search_experience'),
            'search_frequency'=>$request->input('search_frequency'),
            'nonsearch_frequency'=>$request->input('nonsearch_frequency'),
            'consent_datacollection'=>$request->input('consent_datacollection'),
            'consent_audio'=>$request->input('consent_audio'),
            'consent_furtheruse'=>$request->input('consent_furtheruse'),
            'study_date'=>$request->input('study_date'),
        ]);




//        $email = $request->input('email');
//        $user = User::findOrFail($id);
//        Mail::send('emails.confirmation', [], function ($m){
//            $m->from('hello@app.com', 'Your Application');
//
//            $m->to('mmitsui@scarletmail.rutgers.edu', 'Test User')->subject('Your Reminder!');
//        });
//        Mail::send('emails.reminder', ['user' => $user], function ($m){
//            $m->from('hello@app.com', 'Your Application');
//
//            $m->to('mmitsui@scarletmail.rutgers.edu', 'Test User')->subject('Your Reminder!');
//        });
//        Auth::login($this->create($request->all()));

        return redirect('auth/confirmation')->with('registration_confirmed',true);
    }




}
