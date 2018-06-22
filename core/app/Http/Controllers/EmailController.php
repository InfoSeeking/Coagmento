<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Users;
use App\Models\Email;

class EmailController extends Controller
{

    public function __construct()
    {
        //Requires administrative rights.
        $this->middleware('admin', ['except' => null]);

    }

    public function listEmails(){
        $emails = Email::all();
        return view('admin.emails', compact('emails'));
    }

    public function newEmail(){
        return view('admin.create_email');
    }

    public function createEmail(Request $request){
        //$arr=$request->all();
        $this->validate($request, [
            'subject' => 'required',
            'body' => 'required',
        ]);
        $email = Email::create([
            'subject'=>$request->input('subject'),
            'body'=>$request->input('body'),
        ]);
        $email->save();
        $emails = Email::all();
        return view('admin.emails', compact('emails'));
    }


    /**
     * Remove the specified resource from storage.s
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        Email::destroy($id);
        return back();
    }

    public function edit($id){
        $email=Email::find($id);
        return view('admin.edit_email', compact('email'));
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
        $this->validate($request, [
            'subject' => 'required',
            'body' => 'required',
        ]);
        $email = Email::find($id);
        if($email->body != $request->input('body')){
            $email->body = $request->input('body');
        }
        if($email->subject != $request->input('subject')) {
            $email->subject = $request->input('subject');
        }
        $email->save();

        return back();
    }

    /*******************************************************************************************************************
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



}
