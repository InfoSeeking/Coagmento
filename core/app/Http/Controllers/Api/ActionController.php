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
        $action->user_id = $req->user_id;
        $action->project_id = $req->project_id;
        $action->stage_id = $req->stage_id;
       // $action->stage_id =
        $action->action = $req->action;
        $action->value = $req->value;
        $action->json = $req->json;
        $action->action_json = $req->action_json;
        $action->created_at_local = 0;//Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $action->date_local = 0;//Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d');
        $action->created_at_local_ms = 0;//$req->created_at_local_ms;
        $action->save();
    }
}
