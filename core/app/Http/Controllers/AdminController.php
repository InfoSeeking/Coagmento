<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Users;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\MustBeAdministrator;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Models\Project;
use App\Models\Membership;
use App\Models\Demographic;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
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
        $users=User::all();
        return view('admin.manage_users', compact('users'));
    }

    /**
     * Allow management of tasks under the control of specific admin
     */
    public function manageTasks(){
        //$tasks=Task::all()->where('user_id'...);
        return view('admin.manage_tasks'/*, compact('tasks')*/);
    }

    /**
     * Creates a new user using random name and credentials
     */
    public function addUser(Request $request)
    {

//        $this->validate($request, [
//            'age' => 'required',
//            'gender' => 'required',
//            'major' => 'required',
//            'english_first' => 'required',
//            'native_language' => 'required_if:english_first,No',
//            'search_experience' => 'required',
//            'search_frequency' => 'required',
//            'nonsearch_frequency' => 'required',
//            'name'=>'required',
//            'email'=>'required',
//            'password'=>'required'
//        ]);

//        $validator = Auth\AuthController::validator($request->all());

//        $validator->after(function($validator) {
//            if (User::all()->count() >=10) {
//                $validator->errors()->add('field', 'Number of users has reached capacity.');
//            }
//        });
//        if ($validator->fails()) {
//            $this->throwValidationException($request, $validator);
//        }

        $user=AuthController::createRandom();

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

        //Generate random demographics
        $age= rand(18,105);
        $arrGender=['Male','Female'];
        $gender= array_rand($arrGender, 1);
        $arrMajor=['Accounting','Science','Education','Engineering','Computer Science', 'Graphic Design','History','Information Sciences and Technology', 'Architecture', 'Music', 'Pharmacy'];
        $major=array_rand($arrMajor, 1);
        $yearsOnWeb=rand(0,27);//www became public in 1991
        $searchFrequencyArr=['<0.5 hour','>=0.5 hour & <1 hour','>= 1 hour & <1.5 hour','>=1.5 hour & <2 hour',
            '>=2 hour & < 2.5 hour','>=2.5 hour & <3 hour','>=3 hour'];
        $searchFrequency=array_rand($searchFrequencyArr,1);
        $nonSearchArr=['no more than 1 time','2-5 times','5-10 times',
            '11-15 times','16-20 times','21-25 times','more than 25 times'];
        $nonSeach=array_rand($nonSearchArr,1);

        Demographic::create([
            'user_id'=>$user->id,
            'age'=>$age,
            'gender'=>$gender,
            'major'=>$major,
            'native_language'=>'Yes',
            'english_first'=>'Yes',
            'search_experience'=>$yearsOnWeb,
            'search_frequency'=>$searchFrequency,
            'nonsearch_frequency'=>$nonSeach,
            'consent_datacollection'=>true,
            'consent_audio'=>true,
            'consent_furtheruse'=>true,
        ]);

        $user->save();
        $users = User::all();
        return view('/admin/manage_users', compact('users'))->with('registration_confirmed',true);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function editUser($id)
     {
         $user=DB::table('users')->where('id', $id)->first();
         return view('admin.edit_user', compact('user'));
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
         //$user->update($request->all(), $user, $id);
         //$user=DB::table('users')->where('id', $id)->first();
         $user = User::where('id', $id)->first();

         if($request->input('active')){
             $user->active=true;
         }
         else{
             $user->active=false;
         }
         if($request->input('admin')){
             $user->is_admin=true;
         } else {
             $user->is_admin=false;
         }
         $user->save();

         return view('admin.edit_user', compact('user'));

     }

    public function sendCredentials(/*Request $request,*/){
         //$request->session()->flash('alert-success','The credentials have been sent. Please ask the user to check their email.');
         Session::flash('status', 'The credentials have been sent. Please ask the user to check their email.');
         return redirect()->back();
    }

    /**
     * Deletes the specified user with a specific id.
     */
    public function delete($id){
        //$user=User::find($id);
        //$user->delete();
        $users = User::all();
        DB::table('users')->where('id', $id)->delete();

        //return redirect('admin.manage_users', compact('users'));
        return back();
    }

    public function newTask(){
        return view('/admin/add_task');
    }

    public function addTask(Request $request, $id){
        $task= TaskController::create($request, $id);
        return ('/admin/manage_tasks');
    }



    /*****************************************************************************************************************/
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/


}
