<?php

namespace App\Http\Controllers\Api;

use App\Models\CopyAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CopyActionController extends Controller
{
    public function store(Request $req){
        $copyaction = new CopyAction;
        $copyaction->user_id = $req->user_id;
        $copyaction->project_id = $req->project_id;
        $copyaction->stage_id = $req->stage_id;
        $copyaction->url = $req->url;
        $copyaction->title = $req->title;
        $copyaction->snippet = $req->snippet;
        $copyaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $copyaction->created_at_local_ms = $req->created_at_local_ms;
        $copyaction->save();
    }


    public function storeMany(Request $req){
        $copies = $req['copies'];
        $user_id = Auth::user()->id;
        $project_id = 0;
        $stage_id = 0;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
        foreach($copies as $time=>$obj){
//            TODO: Data corrections
            $mouseaction = new CopyAction;
            $mouseaction->user_id = $user_id;
            $mouseaction->project_id = $project_id;
            $mouseaction->stage_id = $stage_id;
            $mouseaction->url = $obj['url'];
            $mouseaction->title = $obj['title'];
            $mouseaction->snippet = $obj['snippet'];
            $mouseaction->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
            $mouseaction->created_at_local_ms = $time;
            $mouseaction->save();
        }
//        Keystroke::insert($data_array);
//        $keystroke->save();
    }
}
