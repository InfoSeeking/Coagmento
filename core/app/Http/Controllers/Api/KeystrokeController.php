<?php

namespace App\Http\Controllers\Api;

use App\Models\Keystroke;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KeystrokeController extends Controller
{
    public function store(Request $req){
        $keystroke = new Keystroke;
        $keystroke->user_id = $req->user_id;
        $keystroke->project_id = $req->project_id;
        $keystroke->stage_id = $req->stage_id;
        $keystroke->code = $req->code;
        $keystroke->which = $req->which;
        $keystroke->modifier = $req->modifier;
        $keystroke->repeat = $req->repeat;
        $keystroke->key = $req->key;
        $keystroke->type = $req->type;
        $keystroke->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $keystroke->created_at_local_ms = $req->created_at_local_ms;
        $keystroke->save();
    }


    public function storeMany(Request $req){
        $keys = $req->keys;
        $user_id = Auth::user()->id;
        $project_id = 1;
        $stage_id = 1;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
        foreach($keys as $time=>$o){
          $obj = $o[0];
            $keystroke = new Keystroke;
            $keystroke->user_id = $user_id;
            $keystroke->project_id = $project_id;
            $keystroke->stage_id = $stage_id;
//            TODO: Accommodate for multiple
            //            TODO: Properly save modifiers
//            TODO: stage_id

            $keystroke->code =   $obj['code'];
            $keystroke->which =  $obj['which'];
            $keystroke->modifier =  $obj['modifier'];
            $keystroke->repeat =  $obj['repeat'];
            $keystroke->key = $obj['key'];
            $keystroke->type = $obj['type'];

            $keystroke->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
            $keystroke->created_at_local_ms = $time;
            $keystroke->save();
//            array_push($data_array,$keystroke);
        }
//        Keystroke::insert($data_array);
//        $keystroke->save();
    }

}
