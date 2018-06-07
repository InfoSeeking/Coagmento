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
        return view('admin.manage_tasks');
    }

    /**
     * Creates a new user using random name and credentials
     */

    public function addUser(Request $request)
    {

        /*$this->validate($request, [
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

        $validator = Auth\AuthController::validator($request->all());

        $validator->after(function($validator) {
            if (User::all()->count() >=10) {
                $validator->errors()->add('field', 'Number of users has reached capacity.');
            }
        });
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }*/
//        else if(User::all()->count() >=10){
//            $validator->errors()->add('messageArea','Number of users has reached capacity.');
////            $this->throwValidationException($request, $validator);
//            throw new ValidationException($validator);
//        }


//        $user = $this->createRandom()->save();

        $user=AuthController::createRandom();

        /*$args = [
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
        ]);*/
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
             $user->admin=1;
         } else {
             $user->admin=0;
         }
         $user->save();

         return view('admin.edit_user', compact('user'));

     }



    /**
     * Deletes the specified user.
     *
     */

    public function delete($id){
        //$user=User::find($id);
        //$user->delete();
        $users = User::all();
        DB::table('users')->where('id', $id)->delete();

        //return redirect('admin.manage_users', compact('users'));
        return back();
    }

    /*public function destroy($id)
    {
        $user=User::destroy($id);
        $users = Auth::user()->all();
        return view('admin.manage_users', compact('users'));
    }*/



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
