<?php

namespace App\Http\Controllers\Api;

use App\Models\PasteAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;

class PasteActionController extends Controller
{
    public function store(Request $req){
        $pasteaction = new PasteAction;
        $pasteaction->user_id = $req->user_id;
        $pasteaction->project_id = $req->project_id;
        $pasteaction->stage_id = $req->stage_id;
        $pasteaction->url = $req->url;
        $pasteaction->title = $req->title;
        $pasteaction->snippet = $req->snippet;
        $pasteaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $pasteaction->created_at_local_ms = $req->created_at_local_ms;
        $pasteaction->save();
    }

    public function storeMany(Request $req){
        $copies = $req['pastes'];
        $user_id = Auth::user()->id;
        $project_id = 0;
        $stage_id = 0;
//        snippet,title,url
        foreach($copies as $time=>$obj){
//            TODO: Data corrections
            $mouseaction = new PasteAction();
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
