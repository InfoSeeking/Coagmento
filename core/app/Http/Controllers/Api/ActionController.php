<?php

namespace App\Http\Controllers\Api;

use App\Models\Action;
use App\Models\Snippet;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ActionController extends Controller
{
    public function store(Request $req){

        // $validator = Validator::make($req, [
        //     'user_id' => 'required',
        //     'project_id' => 'required',
        //     'action' => 'required',
        //     'json' => 'required'
        // ]);
        //
        // if ($validator->fails()) {
        //     return Status::fromValidator($validator);
        // }
        $action = new Action;
        $user_id = Auth::user()->id;
        $project_id = 1;
        $stage_id = 1;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
       // $action->stage_id =
        $action->action = $req->action;
        $action->value = $req->value;
        $action->json = $req->json;
        $action->action_json = $req->action_json;
        $action->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s'); //good
        $action->date_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d');
        $action->created_at_local_ms = $req->created_at_local_ms;
        $action->save();
    }
}
