<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Middleware\MustBeAdministrator;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Models\User;
use App\Models\Project;
use App\Models\Membership;
use App\Models\Demographic;


class AdminController extends Controller
{
    //Taken from AuthController?
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    public function __construct()
    {
        //Requires administrative rights.
        $this->middleware('admin', ['except' => null]);
    }

    /**
     * Display a static administrator page upon login.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Allow management of users under the control of specific admin
     */
    public function manageUsers(){
        return view('admin.manage_users');
    }
    /**
     * Allow management of tasks under the control of specific admin
     */
    public function manageTasks(){
        return view('admin.manage_tasks');
    }

    /*public function addUser(Request $request){

        //$user = Api\UserController::createRandomUser($request);
        //$user = $this->create($request->all());
        Auth::registerRandom($request);
        return redirect('/admin/manage_users',compact('user'))->with('registration_confirmed',true);
    }*/
    /**
     * For the creation of random users by an Administrator
     */
    public function addUser(Request $request)
    {

        $this->validate($request, [
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
//            'password'=>'required'
        ]);

        $validator = $this->validator($request->all());

        $validator->after(function($validator) {
            if (User::all()->count() >=10) {
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


        $user = $this->createRandom();

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
        ]);

        return redirect('/admin/manage_users', compact('user'))->with('registration_confirmed',true);

    }

    public function removeUser(){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function create()
    {
        //
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        //
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function edit($id)
    {
        //
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function update(Request $request, $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        //
    }*/
}
